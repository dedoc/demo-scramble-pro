<?php

namespace Database\Factories;

use App\Enums\JobStatus;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    public function definition(): array
    {
        $minimumSalary = fake()->numberBetween(50_000, 120_000);

        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'status' => fake()->randomElement(JobStatus::cases()),
            'salary_range' => [$minimumSalary, $minimumSalary + 30_000],
        ];
    }
}
