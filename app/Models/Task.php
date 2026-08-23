<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'submission_date',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'is_completed' => 'boolean',
        ];
    }
}
