<?php

namespace App\Http\Requests\Concerns;

trait HasTaskRules
{
    protected function taskRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'submission_date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }
}