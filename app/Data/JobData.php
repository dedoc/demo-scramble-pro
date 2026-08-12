<?php

namespace App\Data;

use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Models\Job;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class JobData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public JobStatus $status,
        /** @var array{int, int} */
        public array $salaryRange,
        public Lazy|CompanyData $company,
        /** @var array{applications: int, rejections: int, approved: int} */
        public Lazy|array $statistics,
    ) {}

    public static function fromModel(Job $job): self
    {
        return new self(
            id: $job->id,
            title: $job->title,
            status: $job->status,
            salaryRange: $job->salary_range,
            company: Lazy::whenLoaded(
                'company',
                $job,
                fn () => CompanyData::from($job->company),
            ),
            statistics: Lazy::create(function () use ($job) {
                return [
                    'applications' => $job->applications()->count(),
                    'rejections' => $job->applications()->where('status', ApplicationStatus::REJECTED)->count(),
                    'approved' => $job->applications()->where('status', ApplicationStatus::APPROVED)->count(),
                ];
            }),
        );
    }
}
