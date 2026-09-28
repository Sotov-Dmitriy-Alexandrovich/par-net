<?php

namespace App\Http\Controllers;

use App\Models\QuitAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuitAttemptController extends Controller
{
    /**
     * История всех попыток пользователя
     */
    public function history()
    {
        $attempts = Auth::user()->quitAttempts()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attempts.history', compact('attempts'));
    }

    /**
     * Статистика по попыткам
     */
    public function stats()
    {
        $user = Auth::user();

        $totalAttempts = $user->quitAttempts()->count();
        $totalCleanDays = $user->quitAttempts()->sum('total_clean_days');
        $longestStreak = $user->quitAttempts()->max('total_clean_days');
        $currentStreak = $user->quitAttempts()
            ->where('status', 'active')
            ->first()?->current_day ?? 0;

        return view('attempts.stats', compact(
            'totalAttempts',
            'totalCleanDays',
            'longestStreak',
            'currentStreak'
        ));
    }
}
