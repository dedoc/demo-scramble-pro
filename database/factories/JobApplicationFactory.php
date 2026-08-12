<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::factory(),
            'job_position_id' => Job::factory(),
            'status' => fake()->randomElement(ApplicationStatus::cases()),
        ];
    }
}
