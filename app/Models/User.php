<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // СВЯЗИ С КАСТОМНЫМИ ТАБЛИЦАМИ

    public function quitAttempts()
    {
        return $this->hasMany(QuitAttempt::class);
    }

    public function reasons()
    {
        return $this->hasMany(Reason::class);
    }

    public function triggers()
    {
        return $this->hasMany(Trigger::class);
    }

    public function doneTasks()
    {
        return $this->hasMany(DoneTask::class);
    }

    public function relapseLogs()
    {
        return $this->hasMany(RelapseLog::class);
    }

    // Активная попытка бросить
    public function activeQuitAttempt()
    {
        return $this->hasOne(QuitAttempt::class)->where('status', 'active');
    }
}
