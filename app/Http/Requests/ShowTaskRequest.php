<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        $canView = $this->user()->can('view', $task);

        ds($canView)->label('ShowTaskRequest: authorize() - can view task?');

        return $canView;
    }

    public function rules(): array
    {
        return [];
    }
}
