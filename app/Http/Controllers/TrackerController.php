<?php

namespace App\Http\Controllers;

use App\Models\QuitAttempt;
use App\Models\Task;
use App\Models\DailyMessage;
use App\Models\DoneTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrackerController extends Controller
{
    /**
     * Главная страница трекера
     */
    public function index()
    {
        $user = Auth::user();

        // Получаем активную попытку пользователя
        $attempt = $user->quitAttempts()
            ->where('status', 'active')
            ->latest()
            ->first();

        // Если нет активной попытки — показываем страницу старта
        if (!$attempt) {
            return view('tracker.start');
        }

        // Вычисляем текущий день
        $currentDay = Carbon::parse($attempt->start_date)->diffInDays(now()) + 1;

        // Обновляем current_day в БД
        if ($attempt->current_day !== $currentDay) {
            $attempt->update(['current_day' => $currentDay]);
        }

        // Получаем задание на сегодня
        $todayTask = Task::where('day_number', $currentDay)->first();

        // Проверяем, выполнено ли оно
        $isTaskCompleted = $todayTask && DoneTask::where('user_id', $user->id)
                ->where('attempt_id', $attempt->id)
                ->where('task_id', $todayTask->id)
                ->exists();

        // Получаем сообщение дня
        $todayMessage = DailyMessage::where('day_number', $currentDay)->first();

        // Статистика
        $stats = [
            'days' => $currentDay,
            'money_saved' => $currentDay * 200, // Примерно 200 руб/день
            'cigarettes_not_smoked' => $currentDay * 10, // Примерно 10 "сигарет"/день
        ];

        return view('tracker.index', compact(
            'attempt',
            'currentDay',
            'todayTask',
            'isTaskCompleted',
            'todayMessage',
            'stats'
        ));
    }

    /**
     * Начать новую попытку бросить
     */
    public function start(Request $request)
    {
        $request->validate([
            'chosen_method' => 'required|in:1,2,3',
        ]);

        // Завершаем предыдущую активную попытку (если есть)
        $user = Auth::user();
        $user->quitAttempts()
            ->where('status', 'active')
            ->update(['status' => 'relapsed']);

        // Создаём новую попытку
        $attempt = QuitAttempt::create([
            'user_id' => $user->id,
            'start_date' => now(),
            'status' => 'active',
            'current_day' => 1,
            'total_clean_days' => 0,
            'relapse_count' => 0,
            'chosen_method' => $request->chosen_method,
        ]);

        return redirect()->route('tracker.index');
    }

    /**
     * Отметить задание как выполненное
     */
    public function completeTask(Request $request, $taskId)
    {
        $user = Auth::user();
        $attempt = $user->quitAttempts()->where('status', 'active')->first();

        if (!$attempt) {
            return response()->json(['error' => 'Нет активной попытки'], 400);
        }

        $task = Task::findOrFail($taskId);
        $currentDay = Carbon::parse($attempt->start_date)->diffInDays(now()) + 1;

        // Проверяем, не выполнено ли уже
        $exists = DoneTask::where('user_id', $user->id)
            ->where('attempt_id', $attempt->id)
            ->where('task_id', $task->id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Задание уже выполнено'], 400);
        }

        // Создаём запись о выполнении
        DoneTask::create([
            'user_id' => $user->id,
            'attempt_id' => $attempt->id,
            'task_id' => $task->id,
            'day_number' => $currentDay,
            'note' => $request->note,
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Задание выполнено!']);
    }

    /**
     * Зафиксировать срыв
     */
    public function relapse(Request $request)
    {
        $user = Auth::user();
        $attempt = $user->quitAttempts()->where('status', 'active')->first();

        if (!$attempt) {
            return redirect()->back()->with('error', 'Нет активной попытки');
        }

        $currentDay = Carbon::parse($attempt->start_date)->diffInDays(now()) + 1;

        // Завершаем текущую попытку
        $attempt->update([
            'status' => 'relapsed',
            'total_clean_days' => $currentDay,
        ]);

        // Увеличиваем счётчик срывов
        $user->increment('relapse_count');

        return redirect()->route('tracker.start')->with('message', 'Срыв зафиксирован. Начни новую попытку!');
    }
}
