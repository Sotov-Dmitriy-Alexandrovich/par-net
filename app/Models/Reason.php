<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'priority',
        'is_anchor',
    ];

    protected $casts = [
        'priority' => 'integer',
        'is_anchor' => 'boolean',
    ];

    // Связь: причина принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
