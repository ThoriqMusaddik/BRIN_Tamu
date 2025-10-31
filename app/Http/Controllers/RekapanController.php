<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu as TamuModel;
use Illuminate\Support\Carbon;

class RekapanController extends Controller
{
    // Export rekapan as CSV (Excel-friendly)
    public function exportExcel(Request $request)
    {
        // Support either month/year selection OR explicit start_date & end_date (YYYY-MM-DD)
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($startDate || $endDate) {
            // both required
            if (! $startDate || ! $endDate) {
                return redirect()->back()->with('error', 'Pilih tanggal mulai dan akhir untuk rentang ekspor.');
            }
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid. Gunakan YYYY-MM-DD.');
            }
            if ($start->gt($end)) {
                return redirect()->back()->with('error', 'Tanggal mulai harus sebelum atau sama dengan tanggal akhir.');
            }
            $label = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
        } else {
            $bulan = (int) $request->query('bulan');
            $tahun = (int) $request->query('tahun');
            if (! $bulan || ! $tahun) {
                return redirect()->back()->with('error', 'Pilih bulan dan tahun atau rentang tanggal.');
            }

            // Build range for month
            try {
                $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Tanggal tidak valid');
            }
            $end = $start->copy()->endOfMonth();
            $label = $start->isoFormat('MMMM YYYY');
        }

        $rows = TamuModel::whereBetween('created_at', [$start, $end])->orderBy('created_at','asc')->get();

        // If no data found, redirect back with message (prevents empty downloads)
        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pada bulan yang dipilih.');
        }

        // Compute summary: distinct instansi count and sum jumlah_orang
        $distinctInstansi = $rows->pluck('asal_instansi')->unique()->filter()->count();
        $totalJumlahOrang = $rows->pluck('jumlah_orang')->map(function($v){ return (int)$v; })->sum();

        $summary = [
            'Periode' => $label,
            'Jumlah Instansi (unik)' => $distinctInstansi,
            'Total Jumlah Orang' => $totalJumlahOrang,
            'Total Rekap' => $rows->count(),
        ];

        // If maatwebsite/excel installed, use it to generate .xlsx
        if (class_exists('Maatwebsite\\Excel\\Facades\\Excel') && class_exists('App\\Exports\\RekapanExport')) {
            $exportClass = 'App\\Exports\\RekapanExport';
            // Build filename from range or month
            if (isset($bulan) && isset($tahun)) {
                $filename = sprintf('rekapan_%04d-%02d.xlsx', $tahun, $bulan);
            } else {
                $filename = 'rekapan_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xlsx';
            }
            // call the Excel facade download method dynamically
            $facade = 'Maatwebsite\\Excel\\Facades\\Excel';
            return forward_static_call_array([$facade, 'download'], [new $exportClass($rows, $summary), $filename]);
        }

        // Fallback: CSV stream
        if (isset($bulan) && isset($tahun)) {
            $filename = sprintf('rekapan_%04d-%02d.csv', $tahun, $bulan);
        } else {
            $filename = 'rekapan_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';
        }
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['ID','Nama','Instansi','Tujuan','PJ','Kontak','Jumlah Orang','Check In','Check Out','Keterangan'];        
        $callback = function() use ($rows, $columns, $summary) {
            $out = fopen('php://output', 'w');
            // BOM for Excel to detect UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            // write summary first
            foreach ($summary as $k => $v) {
                fputcsv($out, [(string)$k, (string)$v]);
            }
            fputcsv($out, []);
            fputcsv($out, $columns);
            $tz = 'Asia/Makassar';
            foreach ($rows as $r) {
                $created = $r->created_at ? Carbon::parse($r->created_at)->setTimezone($tz) : null;
                $checkIn = $created ? $created->format('d/m/Y H:i') : ($r->check_in ?? '-');

                $checkOut = $r->check_out ? Carbon::parse($r->check_out)->setTimezone($tz)->format('d/m/Y H:i') : '-';

                fputcsv($out, [
                    $r->id,
                    $r->nama,
                    $r->asal_instansi,
                    $r->tujuan,
                    $r->pj,
                    $r->kontak,
                    $r->jumlah_orang,
                    $checkIn,
                    $checkOut,
                    $r->keterangan,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export rekapan as PDF (uses dompdf if available)
    public function exportPdf(Request $request)
    {
        // Support month/year or explicit start_date & end_date
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($startDate || $endDate) {
            if (! $startDate || ! $endDate) {
                return redirect()->back()->with('error', 'Pilih tanggal mulai dan akhir untuk rentang ekspor.');
            }
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Format tanggal tidak valid. Gunakan YYYY-MM-DD.');
            }
            if ($start->gt($end)) {
                return redirect()->back()->with('error', 'Tanggal mulai harus sebelum atau sama dengan tanggal akhir.');
            }
            $label = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
        } else {
            $bulan = (int) $request->query('bulan');
            $tahun = (int) $request->query('tahun');
            if (! $bulan || ! $tahun) {
                return redirect()->back()->with('error', 'Pilih bulan dan tahun atau rentang tanggal.');
            }
            try {
                $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Tanggal tidak valid');
            }
            $end = $start->copy()->endOfMonth();
            $label = $start->isoFormat('MMMM YYYY');
        }

        $rows = TamuModel::whereBetween('created_at', [$start, $end])->orderBy('created_at','asc')->get();

        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pada bulan yang dipilih.');
        }

        $data = [
            'rows' => $rows,
            'label' => $label,
            'start' => $start,
            'end' => $end,
        ];

            // If barryvdh/laravel-dompdf is installed, use it. Otherwise render HTML view for manual print.
            if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf') || class_exists('Dompdf\\Dompdf')) {
                try {
                    // Use barryvdh facade if available (call dynamically to avoid parse-time class requirement)
                    if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
                        $pdfObj = call_user_func(['Barryvdh\\DomPDF\\Facade\\Pdf', 'loadView'], 'rekapan_pdf', $data);
                        if (is_object($pdfObj) && method_exists($pdfObj, 'setPaper')) {
                            $pdfObj->setPaper('a4', 'landscape');
                        }
                        if (is_object($pdfObj) && method_exists($pdfObj, 'download')) {
                            if (isset($bulan) && isset($tahun)) {
                                $filename = sprintf('rekapan_%04d-%02d.pdf', $tahun, $bulan);
                            } else {
                                $filename = 'rekapan_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.pdf';
                            }
                            return $pdfObj->download($filename);
                        }
                    }

                    // Fallback: use Dompdf if installed (instantiate dynamically)
                    if (class_exists('Dompdf\\Dompdf')) {
                        $html = view('rekapan_pdf', $data)->render();
                        $dompdfClass = 'Dompdf\\Dompdf';
                        $dompdf = new $dompdfClass();
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('A4', 'landscape');
                        $dompdf->render();
                        $output = $dompdf->output();
                        if (isset($bulan) && isset($tahun)) {
                            $filename = sprintf('rekapan_%04d-%02d.pdf', $tahun, $bulan);
                        } else {
                            $filename = 'rekapan_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.pdf';
                        }
                        return response($output, 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                        ]);
                    }
                } catch (\Exception $e) {
                    // If PDF generation fails, fall through to HTML view
                    return view('rekapan_pdf', $data)->with('warning', 'PDF generation failed: ' . $e->getMessage());
                }
            }

        // Package not available: render HTML view with a notice
        return view('rekapan_pdf', $data)->with('warning', 'Paket PDF tidak terpasang. Silakan install barryvdh/laravel-dompdf untuk mengunduh PDF otomatis.');
    }
}
