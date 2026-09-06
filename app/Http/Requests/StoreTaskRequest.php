<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasTaskRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    use HasTaskRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->taskRules();
    }

    protected function passedValidation(): void
    {
        ds($this->validated())->label('StoreTaskRequest: validated data');
        ds($this->user())->label('StoreTaskRequest: authenticated user');
    }
}