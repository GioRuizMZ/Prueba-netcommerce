<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create(['name' => 'Netcommerce']);

        Company::factory()->count(2)->create();
    }
}
