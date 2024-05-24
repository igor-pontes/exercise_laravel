<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TaskSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([TaskSeeder::class]);
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'test@test.com',
        //     'rank' => 1,
        // ]);
    }
}
