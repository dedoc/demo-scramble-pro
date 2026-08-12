<?php

namespace App\Http\Controllers\Api;

use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Public statistics.
     *
     * Gets aggregated statistics for the website.
     *
     * @unauthenticated
     */
    public function __invoke(Request $request)
    {
        return [
            'candidates' => Candidate::count(),
            'open_jobs' => Job::where('status', JobStatus::OPEN)->count(),
        ];
    }
}
