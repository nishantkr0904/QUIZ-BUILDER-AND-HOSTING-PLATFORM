<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get top users based on quiz scores
        $topUsers = User::withCount(['results as total_score' => function($query) {
            $query->select(\DB::raw('sum(score)'));
        }])
        ->orderBy('total_score', 'desc')
        ->take(10)
        ->get();

        return view('leaderboard', compact('topUsers'));
    }
}
