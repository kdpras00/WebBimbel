<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamificationSetting extends Model
{
    protected $fillable = [
        'nama_aturan',
        'nilai_min',
        'nilai_max',
        'poin',
        'keterangan',
    ];
}
