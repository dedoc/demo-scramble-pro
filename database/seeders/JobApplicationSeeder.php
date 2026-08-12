<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['candidate' => 'alice@example.com', 'job' => 'Senior Backend Engineer', 'status' => ApplicationStatus::APPROVED],
            ['candidate' => 'alice@example.com', 'job' => 'Data Scientist', 'status' => ApplicationStatus::PENDING],
            ['candidate' => 'noah@example.com', 'job' => 'Senior Backend Engineer', 'status' => ApplicationStatus::REJECTED],
            ['candidate' => 'priya@example.com', 'job' => 'Data Scientist', 'status' => ApplicationStatus::APPROVED],
            ['candidate' => 'mateo@example.com', 'job' => 'Engineering Manager', 'status' => ApplicationStatus::PENDING],
            ['candidate' => 'sofia@example.com', 'job' => 'Senior Backend Engineer', 'status' => ApplicationStatus::PENDING],
            ['candidate' => 'sofia@example.com', 'job' => 'Engineering Manager', 'status' => ApplicationStatus::REJECTED],
        ] as $application) {
            $candidate = Candidate::where('email', $application['candidate'])->firstOrFail();
            $job = Job::where('title', $application['job'])->firstOrFail();

            JobApplication::updateOrCreate(
                [
                    'candidate_id' => $candidate->id,
                    'job_position_id' => $job->id,
                ],
                ['status' => $application['status']],
            );
        }
    }
}
