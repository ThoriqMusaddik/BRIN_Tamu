<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

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
        foreach ($this->rows as $r) {
            $out[] = [
                $r->id,
                $r->nama,
                $r->asal_instansi,
                $r->tujuan,
                $r->pj,
                $r->kontak,
                $r->jumlah_orang,
                $r->check_in,
                $r->check_out,
                $r->status,
                $r->keterangan,
                optional($r->created_at)->format('Y-m-d H:i:s'),
            ];
        }
        return $out;
    }

    public function headings(): array
    {
        return ['ID','Nama','Instansi','Tujuan','PJ','Kontak','Jumlah Orang','Check In','Check Out','Status','Keterangan','Tanggal'];
    }

    public function title(): string
    {
        return 'Data';
    }
}
