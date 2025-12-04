<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Point extends Model
{
    protected $fillable = [
        'user_id',
        'total_poin',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBadgeAttribute()
    {
        $points = $this->total_poin;

        if ($points >= 1000) {
            return 'Diamond';
        } elseif ($points >= 500) {
            return 'Gold';
        } elseif ($points >= 200) {
            return 'Silver';
        } else {
            return 'Bronze';
        }
    }
}
