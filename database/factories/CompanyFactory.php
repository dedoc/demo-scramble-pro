<?php

namespace Database\Factories;

use App\Enums\CompanySize;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'size' => fake()->randomElement(CompanySize::cases()),
            'contact' => [
                'name' => fake()->name(),
                'email' => fake()->unique()->companyEmail(),
            ],
        ];
    }
}
