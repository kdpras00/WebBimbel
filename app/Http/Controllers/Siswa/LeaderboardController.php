<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = \App\Models\Point::with('user')
            ->orderBy('total_poin', 'desc')
            ->limit(20)
            ->get();
            
        $userPointRecord = \App\Models\Point::where('user_id', Auth::id())->first();
        
        if (!$userPointRecord) {
            $userPoints = 0;
            $userRank = '-';
        } else {
            $userPoints = $userPointRecord->total_poin;
            $userRank = \App\Models\Point::where('total_poin', '>', $userPoints)->count() + 1;
        }

        return view('siswa.leaderboard.index', compact('leaderboard', 'userRank'));
    }


}
