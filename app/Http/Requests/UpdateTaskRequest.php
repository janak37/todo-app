<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasTaskRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    use HasTaskRules;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    public function rules(): array
    {
        return $this->taskRules();
    }
}
