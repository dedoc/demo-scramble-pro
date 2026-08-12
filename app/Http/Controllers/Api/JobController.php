<?php

namespace App\Http\Controllers\Api;

use App\Data\JobData;
use App\Data\JobPayloadData;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Create job.
     */
    public function store(JobPayloadData $jobPayload)
    {
        $company = Company::findOrFail($jobPayload->companyId);
        $job = $company->jobs()->create([
            'title' => $jobPayload->title,
            'status' => $jobPayload->status,
            'salary_range' => $jobPayload->salaryRange,
        ]);

        return response()->json(JobData::from($job), 201);
    }
}
