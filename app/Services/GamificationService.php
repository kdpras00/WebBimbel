<?php

namespace App\Services;

use App\Models\GamificationSetting;
use App\Models\Point;
use App\Models\QuizResult;

class GamificationService
{
    /**
     * Calculate points based on quiz result
     */
    public function calculatePoints(QuizResult $result): int
    {
        $nilai = $result->nilai;
        
        // Get gamification settings
        $settings = GamificationSetting::orderBy('nilai_min', 'desc')->get();
        
        foreach ($settings as $setting) {
            if ($setting->nilai_min !== null && $setting->nilai_max !== null) {
                if ($nilai >= $setting->nilai_min && $nilai <= $setting->nilai_max) {
                    return $setting->poin;
                }
            } elseif ($setting->nilai_min !== null) {
                if ($nilai >= $setting->nilai_min) {
                    return $setting->poin;
                }
            }
        }
        
        // Default points if no rule matches
        return 10;
    }

    /**
     * Award points to student
     */
    public function awardPoints(int $userId, int $points): void
    {
        $point = Point::firstOrCreate(
            ['user_id' => $userId],
            ['total_poin' => 0]
        );
        
        $point->total_poin += $points;
        $point->save();
    }

    /**
     * Process quiz result and award points
     */
    public function processQuizResult(QuizResult $result): void
    {
        $points = $this->calculatePoints($result);
        $this->awardPoints($result->siswa_id, $points);
    }

    /**
     * Get leaderboard
     */
    public function getLeaderboard(int $limit = 10)
    {
        return Point::with('user')
            ->orderBy('total_poin', 'desc')
            ->limit($limit)
            ->get();
    }
}

