<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $table = 'quiz';

    protected $fillable = [
        'judul',
        'deskripsi',
        'mapel_id',
        'pengajar_id',
        'durasi',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('urutan');
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }
}
