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
        ]);

        // Map form fields to model columns
        $tamu = new TamuModel();
        $tamu->nama = $validated['nama'];
        $tamu->asal_instansi = $validated['instansi'];
        $tamu->tujuan = $validated['tujuan'];
        $tamu->pj = $validated['penanggung_jawab'];

        $now = Carbon::now($tz);
        // store check_in as time (HH:MM)
        $tamu->check_in = $now->format('H:i');
        $tamu->check_out = null;
        $tamu->status = 'IN';
        // hari = Indonesian weekday in uppercase (e.g., SENIN)
        $tamu->hari = strtoupper($now->isoFormat('dddd'));
        $tamu->save();

        return redirect()->route('halaman2');
    }
    
    /**
     * Display a listing of tamu for the admin dashboard.
     */
    public function index()
    {
        // Use Indonesian locale and timezone
        Carbon::setLocale('id');
        $tz = 'Asia/Makassar';

        $now = Carbon::now($tz);

        // Auto-checkout: if a tamu is still 'IN' and was created before today, mark as AUTO_OUT
        $today = $now->startOfDay();
        $toAuto = TamuModel::where('status', 'IN')
            ->whereDate('created_at', '<', $today->toDateString())
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

        // simple pagination, newest first
        $tamus = TamuModel::orderBy('id', 'desc')->paginate(15);
        return view('adminDashboard', [
            'tamus' => $tamus,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount,
            'total' => $total,
        ]);
    }
    
}
