<?php

namespace Database\Seeders;

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
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $this->call([
            CompanySeeder::class,
            JobSeeder::class,
            CandidateSeeder::class,
            JobApplicationSeeder::class,
        ]);
    }
}
