<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'deskripsi',
        'jurusan',
    ];

    public function mapel(): HasMany
    {
        return $this->hasMany(Mapel::class);
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->where('role', 'siswa');
    }

    public function pengajar(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_pengajar', 'kelas_id', 'pengajar_id')
            ->where('role', 'pengajar')
            ->withPivot('mapel_id');
    }
}
