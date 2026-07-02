<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Mapel;
use App\Models\User;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'file_path',
        'konten',
        'mapel_id',
        'pengajar_id',
        'jurusan',
    ];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }
}
