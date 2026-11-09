<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxPendingTasks implements ValidationRule
{
    public function __construct(private int $limit = 5)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pendingTasks = Task::where('user_id', $value)->pending()->count();

        if ($pendingTasks >= $this->limit) {
            $fail("El usuario ya tiene {$this->limit} tareas pendientes.");
        }
    }
}
