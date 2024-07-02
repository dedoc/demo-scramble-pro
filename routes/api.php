<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('company', CompanyController::class)->only(['index', 'store',  'show', 'destroy']);
    Route::apiResource('job', JobController::class)->only(['store']);
});

Route::get('statistics', StatisticsController::class);
