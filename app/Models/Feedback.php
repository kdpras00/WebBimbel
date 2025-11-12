<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'pengajar_id',
        'siswa_id',
        'quiz_result_id',
        'komentar',
    ];

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function quizResult(): BelongsTo
    {
        return $this->belongsTo(QuizResult::class);
    }
}
