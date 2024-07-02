<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'candidates' => 100,
            'open_jobs' => (int) Job::count(),
        ];
    }
}
