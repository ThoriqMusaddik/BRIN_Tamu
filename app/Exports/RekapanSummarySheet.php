<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapanSummarySheet implements FromArray, WithTitle
{
    protected $summaryArray;

    public function __construct(array $summary)
    {
        $this->summaryArray = $summary;
    }

    public function array(): array
    {
        // Return as array of arrays (each sub-array is a row)
        $out = [];
        foreach ($this->summaryArray as $k => $v) {
            $out[] = [$k, $v];
        }
        return $out;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}
