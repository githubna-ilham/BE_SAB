<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $table = 'catatan';

    protected $fillable = ['judul', 'isi', 'kategori', 'dibuat_pada'];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];
}
