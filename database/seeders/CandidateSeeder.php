<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Alice Walker', 'email' => 'alice@example.com'],
            ['name' => 'Noah Williams', 'email' => 'noah@example.com'],
            ['name' => 'Priya Shah', 'email' => 'priya@example.com'],
            ['name' => 'Mateo Garcia', 'email' => 'mateo@example.com'],
            ['name' => 'Sofia Rossi', 'email' => 'sofia@example.com'],
        ] as $candidate) {
            Candidate::updateOrCreate(['email' => $candidate['email']], $candidate);
        }
    }
}
