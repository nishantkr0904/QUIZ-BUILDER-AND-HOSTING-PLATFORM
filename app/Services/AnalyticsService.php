<?php
namespace App\Services;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;

class AnalyticsService
{
    /**
     * Get summary statistics for admin dashboard.
     * @return array
     */
    public function getSummary()
    {
        return [
            'total_quizzes' => Quiz::count(),
            'total_users' => User::count(),
            'total_attempts' => Result::count(),
        ];
    }

    /**
     * Get leaderboard data.
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getLeaderboard($limit = 10)
    {
        return User::with(['results' => function($q) {
            $q->orderByDesc('score');
        }])
        ->get()
        ->map(function($user) {
            $total = $user->results->sum('score');
            return [
                'user' => $user->name,
                'score' => $total,
            ];
        })
        ->sortByDesc('score')
        ->take($limit)
        ->values();
    }
}
