<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        ds($user->id, $task->user_id)->label('view: user->id vs task->user_id');

        abort_unless($task->user_id === $user->id, 404);

        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        ds($user->id, $task->user_id)->label('update: user->id vs task->user_id');

        abort_unless($task->user_id === $user->id, 404);

        return true;
    }

    public function delete(User $user, Task $task): bool
    {
        abort_unless($task->user_id === $user->id, 404);

        return true;
    }
}
