<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'is_completed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'active' => $query->where('is_completed', false)
                ->where(function (Builder $q) {
                    $q->whereNull('submission_date')
                        ->orWhere('submission_date', '>=', today());
                }),
            'completed' => $query->where('is_completed', true),
            'overdue' => $query->where('is_completed', false)
                ->whereNotNull('submission_date')
                ->where('submission_date', '<', today()),
            default => $query,
        };
    }

    public function scopeOrdered(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'due_asc' => $query->orderBy('submission_date', 'asc'),
            'due_desc' => $query->orderBy('submission_date', 'desc'),
            default => $query->latest(),
        };
    }
}
