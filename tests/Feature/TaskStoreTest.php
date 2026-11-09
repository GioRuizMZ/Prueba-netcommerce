<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_task_for_a_user_and_company(): void
    {
        $company = Company::factory()->create(['name' => 'Netcommerce']);
        $user = User::factory()->create(['name' => 'Akira']);

        $response = $this->postJson('/api/tasks/create', [
            'company_id' => $company->id,
            'name' => 'task 4',
            'description' => 'task content',
            'user_id' => $user->id,
        ]);

        $task = Task::first();

        $response->assertCreated()->assertExactJson([
            'id' => $task->id,
            'name' => 'task 4',
            'description' => 'task content',
            'user' => 'Akira',
            'company' => [
                'id' => $company->id,
                'name' => 'Netcommerce',
            ],
        ]);

        $this->assertFalse($task->is_completed);
        $this->assertNotNull($task->start_at);
    }

    public function test_requires_company_user_and_name(): void
    {
        $response = $this->post('/api/tasks/create', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id', 'user_id', 'name']);
    }

    public function test_rejects_company_and_user_that_do_not_exist(): void
    {
        $response = $this->postJson('/api/tasks/create', [
            'company_id' => 999,
            'name' => 'task 4',
            'user_id' => 999,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['company_id', 'user_id']);
    }

    public function test_rejects_a_sixth_pending_task_for_the_same_user(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        Task::factory()->count(5)->pending()->for($user)->create();

        $response = $this->postJson('/api/tasks/create', [
            'company_id' => $company->id,
            'name' => 'task 6',
            'user_id' => $user->id,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['user_id']);
        $this->assertSame(5, $user->tasks()->count());
    }
}
