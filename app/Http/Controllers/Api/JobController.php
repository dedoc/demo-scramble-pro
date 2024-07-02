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
    public function store(Company $company, JobPayloadData $jobPayload)
    {
        $job = Job::make($jobPayload);

        $company->jobs()->save($job);

        return JobData::from($job);
    }
}
