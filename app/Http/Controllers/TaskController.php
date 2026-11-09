<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\CreatedTaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            ...$request->validated(),
            'start_at' => now(),
        ]);

        return new CreatedTaskResource($task->load('user', 'company'));
    }
}
