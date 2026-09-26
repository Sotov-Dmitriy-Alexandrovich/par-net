<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
    ];

    // Связь: триггер принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
