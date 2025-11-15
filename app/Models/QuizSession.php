<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSession extends Model
{
    protected $fillable = [
        'quiz_id',
        'siswa_id',
        'status',
        'warning_count',
        'server_remaining_seconds',
        'started_at',
        'last_resumed_at',
        'paused_at',
        'submitted_at',
        'question_order',
        'option_mapping',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_resumed_at' => 'datetime',
        'paused_at' => 'datetime',
        'submitted_at' => 'datetime',
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

    public function remainingSeconds(?CarbonInterface $now = null): ?int
    {
        if (is_null($this->server_remaining_seconds)) {
            return null;
        }

        $now = $now ?: now();

        if ($this->status === 'active') {
            $lastResume = $this->last_resumed_at ?? $this->started_at ?? $this->created_at;
            if (!$lastResume) {
                return $this->server_remaining_seconds;
            }

            $elapsed = $lastResume->diffInSeconds($now);

            return max(0, $this->server_remaining_seconds - $elapsed);
        }

        return max(0, $this->server_remaining_seconds);
    }
}


