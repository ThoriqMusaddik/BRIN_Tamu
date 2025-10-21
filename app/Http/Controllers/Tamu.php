<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu as TamuModel;
use Illuminate\Support\Carbon;

class Tamu extends Controller
{
    //Tamu controller
    /**
     * Store a new tamu (visitor) from the public form (halaman1).
     * Accepts both normal check-in submissions and checkout mode (only nama+instansi).
     */
    public function store(Request $request)
    {
        // Use Indonesian locale and local timezone (WITA - Asia/Makassar) for day and time
        Carbon::setLocale('id');
        $tz = 'Asia/Makassar';

        // Determine if this is a checkout action from the modal
        $mode = $request->input('mode');

        if ($mode === 'checkout') {
            // For checkout, we expect nama and instansi; find latest matching open visit and set check_out/status
            $data = $request->validate([
                'nama' => ['required','string','max:255'],
                'instansi' => ['required','string','max:255'],
            ]);

            // find the most recent tamu with same nama and asal_instansi and status 'IN'
            $tamu = TamuModel::where('nama', $data['nama'])
                ->where('asal_instansi', $data['instansi'])
                ->where('status', 'IN')
                ->latest('id')
                ->first();

            if ($tamu) {
                $now = Carbon::now($tz);
                $tamu->check_out = $now->format('H:i');
                $tamu->status = 'OUT';
                $tamu->save();
            }

            return redirect()->route('halaman2');
        }

        // Normal check-in flow
        $validated = $request->validate([
            'nama' => ['required','string','max:255'],
            'instansi' => ['required','string','max:255'],
            'tujuan' => ['required','string','max:255'],
            'penanggung_jawab' => ['required','string','max:255'],
            'kontak' => ['nullable','string','max:255'],
            'jumlah_orang' => ['nullable','integer','min:1'],
        ]);

        // Map form fields to model columns
        $tamu = new TamuModel();
        $tamu->nama = $validated['nama'];
        $tamu->asal_instansi = $validated['instansi'];
        $tamu->tujuan = $validated['tujuan'];
        $tamu->pj = $validated['penanggung_jawab'];
    $tamu->kontak = $request->input('kontak');
    $tamu->jumlah_orang = $request->input('jumlah_orang') ? (int) $request->input('jumlah_orang') : 1;

        // stay_until: optional date input; default = today
        $stayUntilInput = $request->input('stay_until');
        $now = Carbon::now($tz);
        if ($stayUntilInput) {
            try {
                $stayUntil = Carbon::parse($stayUntilInput)->startOfDay();
            } catch (\Exception $e) {
                $stayUntil = $now->copy()->startOfDay();
            }
        } else {
            $stayUntil = $now->copy()->startOfDay();
        }
        // store check_in as time (HH:MM)
    $tamu->check_in = $now->format('H:i');
        $tamu->check_out = null;
        $tamu->status = 'IN';
    $tamu->stay_until = $stayUntil->toDateString();
        // hari = Indonesian weekday in uppercase (e.g., SENIN)
        $tamu->hari = strtoupper($now->isoFormat('dddd'));
        $tamu->save();

        return redirect()->route('halaman2');
    }
    
    /**
     * Display a listing of tamu for the admin dashboard.
     */
    public function index(Request $request)
    {
        // Use Indonesian locale and timezone
        Carbon::setLocale('id');
        $tz = 'Asia/Makassar';

        $now = Carbon::now($tz);

        // Auto-checkout: if a tamu is still 'IN' and was created before today AND stay_until < today, mark as AUTO_OUT
        $today = $now->startOfDay();
        $toAuto = TamuModel::where('status', 'IN')
            ->whereDate('created_at', '<', $today->toDateString())
            ->where(function($q) use ($today){
                // either stay_until is null or stay_until < today
                $q->whereNull('stay_until')
                  ->orWhereDate('stay_until', '<', $today->toDateString());
            })
            ->get();

        foreach ($toAuto as $t) {
            // Only update if still IN
            $t->check_out = '23:59';
            $t->status = 'AUTO_OUT';
            $t->save();
        }

        // Statistics
        $todayCount = TamuModel::whereDate('created_at', $now->toDateString())->count();
        $weekStart = (clone $now)->startOfWeek();
        $weekEnd = (clone $now)->endOfWeek();
        $weekCount = TamuModel::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        $total = TamuModel::count();

        // per-page selection (allowed values)
        $allowed = [10,15,25,50,100];
        $perPage = (int) $request->query('per_page', 15);
        if (! in_array($perPage, $allowed)) {
            $perPage = 15;
        }

        // simple pagination, newest first
        $tamus = TamuModel::orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return view('adminDashboard', [
            'tamus' => $tamus,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount,
            'total' => $total,
            'perPage' => $perPage,
        ]);
    }
    
    /**
     * Delete a single tamu by id.
     */
    public function destroy($id)
    {
        // Only allow non-resepsionis to delete
        if (auth()->check() && auth()->user()->isResepsionis()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $t = TamuModel::find($id);
        if ($t) {
            $t->delete();
        }
        return redirect()->back();
    }

    /**
     * Bulk delete tamu by array of ids in request->ids
     */
    public function bulkDestroy(Request $request)
    {
        // Only allow non-resepsionis to bulk delete
        if (auth()->check() && auth()->user()->isResepsionis()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $idsInput = $request->input('ids');

        // Normalize input: accept JSON string or array
        if (is_string($idsInput)) {
            $decoded = json_decode($idsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $ids = array_map('intval', $decoded);
            } else {
                return redirect()->back()->with('error', 'Data ids tidak valid');
            }
        } elseif (is_array($idsInput)) {
            $ids = array_map('intval', $idsInput);
        } else {
            return redirect()->back()->with('error', 'Data ids tidak ditemukan');
        }

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data dipilih');
        }

        // only delete existing ids
        $existing = TamuModel::whereIn('id', $ids)->pluck('id')->map(function($v){ return (int) $v; })->toArray();
        if (empty($existing)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $deleted = TamuModel::whereIn('id', $existing)->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus ' . count($existing) . ' data');
    }
    
    /**
     * Update keterangan for a tamu (admin only)
     */
    public function updateKeterangan(Request $request, $id)
    {
        if (! auth()->check() || auth()->user()->isResepsionis()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memberi keterangan.');
        }

        $t = TamuModel::find($id);
        if (! $t) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $data = $request->validate([
            'keterangan' => ['nullable','string','max:2000'],
        ]);

        $t->keterangan = $data['keterangan'] ?? null;
        $t->save();

        return redirect()->back()->with('success', 'Keterangan berhasil disimpan');
    }
    
}
