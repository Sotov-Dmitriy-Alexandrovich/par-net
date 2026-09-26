<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelapseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attempt_id',
        'day_number_when_relapsed',
        'trigger_type',
        'what_happened',
        'what_i_could_do_differently',
        'lesson_learned',
    ];

    protected $casts = [
        'day_number_when_relapsed' => 'integer',
    ];

    // Связь: запись о срыве принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: запись о срыве принадлежит попытке
    public function attempt()
    {
        return $this->belongsTo(QuitAttempt::class, 'attempt_id');
    }
}
