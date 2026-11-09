<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Akira',
            'email' => 'akira@netcommerce.com',
        ]);

        User::factory()->count(4)->create();
    }
}
