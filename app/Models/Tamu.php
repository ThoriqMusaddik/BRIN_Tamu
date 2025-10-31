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
        'jumlah_orang',
        'kontak',
        'keterangan',
    ];

    /**
     * Casts for date/datetime fields
     */
    protected $casts = [
        'check_out' => 'datetime',
        'stay_until' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
