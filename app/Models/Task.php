<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_number',
        'title',
        'description',
        'type',
        'icon',
        'difficulty',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'difficulty' => 'integer',
    ];

    // Связь: у задания может быть много выполнений
    public function doneTasks()
    {
        return $this->hasMany(DoneTask::class);
    }
}
