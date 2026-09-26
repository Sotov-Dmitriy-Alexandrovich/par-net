<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoneTask extends Model
{
    use HasFactory;

    // ВАЖНО: кастомное имя таблицы!
    protected $table = 'done_tasks';

    protected $fillable = [
        'user_id',
        'attempt_id',
        'task_id',
        'day_number',
        'note',
        'completed_at',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'completed_at' => 'datetime',
    ];

    // Связь: выполненное задание принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: выполненное задание принадлежит попытке
    public function attempt()
    {
        return $this->belongsTo(QuitAttempt::class, 'attempt_id');
    }

    // Связь: выполненное задание относится к заданию
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
