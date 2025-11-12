<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'pertanyaan',
        'tipe',
        'pilihan',
        'jawaban_benar',
        'skor',
        'urutan',
    ];

    protected $casts = [
        'pilihan' => 'array',
    ];

    public function getPilihanAttribute($value)
    {
        // Jika sudah array, return langsung
        if (is_array($value)) {
            return $value;
        }
        
        // Jika string (JSON), decode
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            
            // Jika hasil decode masih string (double encoding), decode lagi
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            
            return is_array($decoded) ? $decoded : [];
        }
        
        // Default return empty array
        return [];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
