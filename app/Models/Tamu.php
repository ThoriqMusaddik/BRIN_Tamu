<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    //Model untuk tabel 'tamu'
    protected $table = 'tamu';

    protected $fillable = [
        'nama',
        'asal_instansi',
        'tujuan',
        'pj',
        'check_in',
        'check_out',
        'status',
        'hari',
        'stay_until',
    ];
}
