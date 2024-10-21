<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => 1,
            'name' => 'Hieu',
            'email' => 'hieu@example.com',
            'password' => bcrypt('123.321A'),
            'email_verified_at' => time()
        ]);
        User::factory()->create([
            'id' => 2,
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('123.321A'),
            'email_verified_at' => time()
        ]);

        Shipment::factory()
            ->count(30)
            ->hasSchedules(30)
            ->create();
    }
}
