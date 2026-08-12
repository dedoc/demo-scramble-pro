<?php

namespace Database\Seeders;

use App\Enums\JobStatus;
use App\Models\Company;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['company' => 'Acme Analytics', 'title' => 'Senior Backend Engineer', 'status' => JobStatus::OPEN, 'salary_range' => [120_000, 155_000]],
            ['company' => 'Acme Analytics', 'title' => 'Product Designer', 'status' => JobStatus::CLOSED, 'salary_range' => [95_000, 125_000]],
            ['company' => 'Northstar Labs', 'title' => 'Data Scientist', 'status' => JobStatus::OPEN, 'salary_range' => [110_000, 145_000]],
            ['company' => 'Juniper Works', 'title' => 'Engineering Manager', 'status' => JobStatus::OPEN, 'salary_range' => [145_000, 185_000]],
        ] as $job) {
            $company = Company::where('name', $job['company'])->firstOrFail();

            $company->jobs()->updateOrCreate(
                ['title' => $job['title']],
                ['status' => $job['status'], 'salary_range' => $job['salary_range']],
            );
        }
    }
}
