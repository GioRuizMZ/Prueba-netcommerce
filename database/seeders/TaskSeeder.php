<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $netcommerce = Company::firstWhere('name', 'Netcommerce');
        $akira = User::firstWhere('email', 'akira@netcommerce.com');

        Task::factory()->pending()->for($netcommerce)->for($akira)->create([
            'name' => 'task 1',
            'description' => 'task content',
        ]);

        Task::factory()->completed()->for($netcommerce)->for($akira)->create([
            'name' => 'task 2',
            'description' => 'task content',
        ]);

        $companies = Company::all();
        $otherUsers = User::where('id', '!=', $akira->id)->get();

        foreach ($otherUsers as $user) {
            Task::factory()
                ->count(3)
                ->for($user)
                ->recycle($companies)
                ->sequence(['is_completed' => false], ['is_completed' => true])
                ->create();
        }
    }
}
