<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_number',
        'title',
        'text',
        'is_milestone',
    ];

    protected $casts = [
        'day_number' => 'integer',
        'is_milestone' => 'boolean',
    ];
}
