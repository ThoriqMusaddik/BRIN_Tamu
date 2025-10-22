<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapanExport implements WithMultipleSheets
{
    protected $rows;
    protected $summary;

    public function __construct($rows, $summary = [])
    {
        $this->rows = $rows;
        $this->summary = $summary;
    }

    public function sheets(): array
    {
        return [
            new RekapanSummarySheet($this->summary),
            new RekapanDataSheet($this->rows),
        ];
    }
}
