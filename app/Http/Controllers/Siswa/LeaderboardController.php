<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $leaderboard = $this->gamificationService->getLeaderboard(20);
        $userRank = $this->getUserRank(Auth::id());

        return view('siswa.leaderboard.index', compact('leaderboard', 'userRank'));
    }

    private function getUserRank($userId)
    {
        $points = \App\Models\Point::orderBy('total_poin', 'desc')->get();
        $rank = 1;
        
        foreach ($points as $point) {
            if ($point->user_id == $userId) {
                return $rank;
            }
            $rank++;
        }
        
        return null;
    }
}
