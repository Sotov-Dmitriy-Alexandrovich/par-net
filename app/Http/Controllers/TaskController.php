<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\DoneTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Список всех заданий
     */
    public function index()
    {
        $tasks = Task::orderBy('day_number')->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Прогресс выполнения заданий
     */
    public function progress()
    {
        $user = Auth::user();
        $attempt = $user->quitAttempts()->where('status', 'active')->first();

        if (!$attempt) {
            return view('tasks.progress', ['completedCount' => 0, 'totalCount' => 0]);
        }

        $completedCount = DoneTask::where('user_id', $user->id)
            ->where('attempt_id', $attempt->id)
            ->count();

        $totalCount = Task::count();

        return view('tasks.progress', compact('completedCount', 'totalCount'));
    }
}
