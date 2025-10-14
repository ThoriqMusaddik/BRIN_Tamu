<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //model untuk tabel 'admin'
    protected $table = 'admin';

    protected $fillable = [
        'nama',
        'username',
        'password',
    ];
}
