<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_companies_with_their_tasks(): void
    {
        $company = Company::factory()->create(['name' => 'Netcommerce']);
        $user = User::factory()->create(['name' => 'Akira']);
        $task = Task::factory()->pending()->for($company)->for($user)->create([
            'name' => 'task 1',
            'description' => 'task content',
            'start_at' => '2023-08-04 00:00:00',
            'expired_at' => null,
        ]);

        $response = $this->getJson('/api/companies');

        $response->assertOk()->assertExactJson([
            [
                'id' => $company->id,
                'name' => 'Netcommerce',
                'tasks' => [
                    [
                        'id' => $task->id,
                        'name' => 'task 1',
                        'description' => 'task content',
                        'user' => 'Akira',
                        'is_completed' => 0,
                        'start_at' => '2023-08-04 00:00:00',
                        'expired_at' => null,
                    ],
                ],
            ],
        ]);
    }

    public function test_includes_companies_without_tasks(): void
    {
        Company::factory()->count(2)->create();

        $response = $this->getJson('/api/companies');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.tasks', [])
            ->assertJsonPath('1.tasks', []);
    }

    public function test_filters_companies_by_name_when_search_is_given(): void
    {
        Company::factory()->create(['name' => 'Netcommerce']);
        Company::factory()->create(['name' => 'Acme']);

        $response = $this->getJson('/api/companies?search=net');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Netcommerce');
    }

    public function test_returns_all_companies_when_search_is_empty(): void
    {
        Company::factory()->count(3)->create();

        $this->getJson('/api/companies?search=')->assertOk()->assertJsonCount(3);
    }
}
