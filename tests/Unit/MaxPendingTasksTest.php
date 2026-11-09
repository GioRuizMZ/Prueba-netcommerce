<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Rules\MaxPendingTasks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class MaxPendingTasksTest extends TestCase
{
    use RefreshDatabase;

    private function validate(User $user): bool
    {
        return Validator::make(
            ['user_id' => $user->id],
            ['user_id' => [new MaxPendingTasks]]
        )->passes();
    }

    public function test_passes_when_user_has_less_than_five_pending_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(4)->pending()->for($user)->create();

        $this->assertTrue($this->validate($user));
    }

    public function test_fails_when_user_already_has_five_pending_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(5)->pending()->for($user)->create();

        $this->assertFalse($this->validate($user));
    }

    public function test_completed_tasks_do_not_count_towards_the_limit(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(4)->pending()->for($user)->create();
        Task::factory()->count(3)->completed()->for($user)->create();

        $this->assertTrue($this->validate($user));
    }

    public function test_only_counts_tasks_of_the_given_user(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(5)->pending()->create();

        $this->assertTrue($this->validate($user));
    }
}
