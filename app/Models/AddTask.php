<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task',
        'status',
        'start_date',
        'due_date'
    ];

    protected $attributes = [
        'status' => 'On Progress'
    ];
}
