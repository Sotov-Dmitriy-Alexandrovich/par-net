<?php

namespace App\Http\Controllers;

use App\Models\RelapseLog;
use App\Models\QuitAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RelapseController extends Controller
{
    /**
     * Форма записи о срыве
     */
    public function create()
    {
        return view('relapse.create');
    }

    /**
     * Сохранить запись о срыве
     */
    public function store(Request $request)
    {
        $request->validate([
            'trigger_type' => 'required|in:stress,boredom,friends,advertising,other',
            'what_happened' => 'required|string|max:1000',
            'what_i_could_do_differently' => 'required|string|max:1000',
            'lesson_learned' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $attempt = $user->quitAttempts()->where('status', 'active')->first();

        if (!$attempt) {
            return redirect()->back()->with('error', 'Нет активной попытки');
        }

        $currentDay = Carbon::parse($attempt->start_date)->diffInDays(now()) + 1;

        // Создаём запись о срыве
        RelapseLog::create([
            'user_id' => $user->id,
            'attempt_id' => $attempt->id,
            'day_number_when_relapsed' => $currentDay,
            'trigger_type' => $request->trigger_type,
            'what_happened' => $request->what_happened,
            'what_i_could_do_differently' => $request->what_i_could_do_differently,
            'lesson_learned' => $request->lesson_learned,
        ]);

        // Завершаем текущую попытку
        $attempt->update([
            'status' => 'relapsed',
            'total_clean_days' => $currentDay,
            'relapse_count' => $attempt->relapse_count + 1,
        ]);

        return redirect()->route('tracker.start')
            ->with('message', 'Срыв зафиксирован. Проанализируй его и начни заново!');
    }

    /**
     * История срывов пользователя
     */
    public function history()
    {
        $relapses = Auth::user()->relapseLogs()
            ->with('attempt')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('relapse.history', compact('relapses'));
    }
}
