<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class RekapanDataSheet implements FromArray, WithHeadings, WithTitle
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        $out = [];
        $tz = 'Asia/Makassar';
        foreach ($this->rows as $r) {
            // Format check_in from created_at and check_out as local datetime
            $created = $r->created_at ? Carbon::parse($r->created_at)->setTimezone($tz) : null;
            $checkIn = $created ? $created->format('d/m/Y H:i') : '-';
            $checkOut = $r->check_out ? Carbon::parse($r->check_out)->setTimezone($tz)->format('d/m/Y H:i') : '-';

            $out[] = [
                $r->id,
                $r->nama,
                $r->asal_instansi,
                $r->tujuan,
                $r->pj,
                $r->kontak,
                $r->jumlah_orang,
                $checkIn,
                $checkOut,
                $r->status,
                $r->keterangan,
            ];
        }
        return $out;
    }

    public function headings(): array
    {
        return ['ID','Nama','Instansi','Tujuan','PJ','Kontak','Jumlah Orang','Check In','Check Out','Status','Keterangan'];
    }

    public function title(): string
    {
        return 'Data';
    }
}
