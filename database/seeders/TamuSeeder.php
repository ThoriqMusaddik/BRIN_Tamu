<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tamu')->insert([[
            'nama' => 'John Doe',
            'asal_instansi' => 'Company A',
            'tujuan' => 'Meeting',
            'pj' => 'Jane Smith',
            'check_in' => Carbon::now()->format('H:i'),
            'check_out' => null,
            'status' => 'IN',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'Alice Johnson',
            'asal_instansi' => 'Company B',
            'tujuan' => 'Interview',
            'pj' => 'Bob Brown',
            'check_in' => Carbon::now()->subHours(2)->format('H:i'),
            'check_out' => Carbon::now()->subHours(1),
            'status' => 'OUT',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'Michael Smith',
            'asal_instansi' => 'Company C',
            'tujuan' => 'Consultation',
            'pj' => 'Sara White',
            'check_in' => Carbon::now()->subHours(3)->format('H:i'),
            'check_out' => null,
            'status' => 'IN',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'Emily Davis',
            'asal_instansi' => 'Company D',
            'tujuan' => 'Workshop',
            'pj' => 'Tom Green',
            'check_in' => Carbon::now()->subHours(4)->format('H:i'),
            'check_out' => Carbon::now()->subHours(2),
            'status' => 'OUT',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'David Wilson',
            'asal_instansi' => 'Company E',
            'tujuan' => 'Training',
            'pj' => 'Linda Black',
            'check_in' => Carbon::now()->subHours(1)->format('H:i'),
            'check_out' => null,
            'status' => 'IN',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'David Wilson',
            'asal_instansi' => 'Company E',
            'tujuan' => 'Training',
            'pj' => 'Linda Black',
            'check_in' => Carbon::now()->subHours(1)->format('H:i'),
            'check_out' => null,
            'status' => 'IN',
            'hari' => Carbon::now()->format('l'),
        ],
        [
            'nama' => 'David Wilson',
            'asal_instansi' => 'Company E',
            'tujuan' => 'Training',
            'pj' => 'Linda Black',
            'check_in' => Carbon::now()->subHours(1)->format('H:i'),
            'check_out' => null,
            'status' => 'IN',
            'hari' => Carbon::now()->format('l'),
        ]]);
    }
}
