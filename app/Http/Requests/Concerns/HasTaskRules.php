<?php

namespace App\Http\Requests\Concerns;

trait HasTaskRules
{
    protected function taskRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'submission_date' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }
}