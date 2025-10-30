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
        $bulan = (int) $request->query('bulan');
        $tahun = (int) $request->query('tahun');
        if (! $bulan || ! $tahun) {
            return redirect()->back()->with('error', 'Pilih bulan dan tahun.');
        }

        // Build range for month
        try {
            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tanggal tidak valid');
        }
        $end = $start->copy()->endOfMonth();

        $rows = TamuModel::whereBetween('created_at', [$start, $end])->orderBy('created_at','asc')->get();

        // If no data found, redirect back with message (prevents empty downloads)
        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pada bulan yang dipilih.');
        }

        // Compute summary: distinct instansi count and sum jumlah_orang
        $distinctInstansi = $rows->pluck('asal_instansi')->unique()->filter()->count();
        $totalJumlahOrang = $rows->pluck('jumlah_orang')->map(function($v){ return (int)$v; })->sum();

        $summary = [
            'Bulan' => $start->isoFormat('MMMM YYYY'),
            'Jumlah Instansi (unik)' => $distinctInstansi,
            'Total Jumlah Orang' => $totalJumlahOrang,
            'Total Rekap' => $rows->count(),
        ];

        // If maatwebsite/excel installed, use it to generate .xlsx
        if (class_exists('Maatwebsite\\Excel\\Facades\\Excel') && class_exists('App\\Exports\\RekapanExport')) {
            $exportClass = 'App\\Exports\\RekapanExport';
            $filename = sprintf('rekapan_%04d-%02d.xlsx', $tahun, $bulan);
            // call the Excel facade download method dynamically
            $facade = 'Maatwebsite\\Excel\\Facades\\Excel';
            return forward_static_call_array([$facade, 'download'], [new $exportClass($rows, $summary), $filename]);
        }

        // Fallback: CSV stream
        $filename = sprintf('rekapan_%04d-%02d.csv', $tahun, $bulan);
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
        $bulan = (int) $request->query('bulan');
        $tahun = (int) $request->query('tahun');
        if (! $bulan || ! $tahun) {
            return redirect()->back()->with('error', 'Pilih bulan dan tahun.');
        }

        try {
            $start = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tanggal tidak valid');
        }
        $end = $start->copy()->endOfMonth();

        $rows = TamuModel::whereBetween('created_at', [$start, $end])->orderBy('created_at','asc')->get();

        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pada bulan yang dipilih.');
        }

        $data = [
            'rows' => $rows,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'label' => $start->isoFormat('MMMM YYYY'),
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
                            return $pdfObj->download(sprintf('rekapan_%04d-%02d.pdf', $tahun, $bulan));
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
                        return response($output, 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="' . sprintf('rekapan_%04d-%02d.pdf', $tahun, $bulan) . '"',
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
