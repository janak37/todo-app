<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

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

    public static function completedCount(): int
    {
        return static::where('is_completed', true)->count();
    }
}
