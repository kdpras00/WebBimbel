<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'siswa_id',
        'nilai',
        'total_soal',
        'jawaban_benar',
        'waktu_pengerjaan',
        'attempt',
        'jawaban',
        'question_order',
        'option_mapping',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'question_order' => 'array',
        'option_mapping' => 'array',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
