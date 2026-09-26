<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuitAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'status',
        'current_day',
        'total_clean_days',
        'relapse_count',
        'chosen_method',
    ];

    protected $casts = [
        'start_date' => 'date',
        'current_day' => 'integer',
        'total_clean_days' => 'integer',
        'relapse_count' => 'integer',
        'chosen_method' => 'integer',
    ];

    // Связь: попытка принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: у попытки много выполненных заданий
    public function doneTasks()
    {
        return $this->hasMany(DoneTask::class, 'attempt_id');
    }

    // Связь: у попытки много записей о срывах
    public function relapseLogs()
    {
        return $this->hasMany(RelapseLog::class, 'attempt_id');
    }

    // Активная попытка пользователя
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
