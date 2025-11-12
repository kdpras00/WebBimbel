<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kelas_id',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class);
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
