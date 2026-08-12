<?php

namespace App\Data;

use App\Enums\JobStatus;
use App\Models\Job;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class JobListData extends Data
{
    /**
     * @param  array{int, int}|Optional  $salaryRange
     */
    public function __construct(
        public int|Optional $id,
        public string|Optional $title,
        public JobStatus|Optional $status,
        public array|Optional $salaryRange,
    ) {}

    public static function fromModel(Job $job): self
    {
        return new self(
            id: self::attribute($job, 'id'),
            title: self::attribute($job, 'title'),
            status: self::attribute($job, 'status'),
            salaryRange: self::attribute($job, 'salary_range'),
        );
    }

    private static function attribute(Job $job, string $key): mixed
    {
        $requestedFields = request()->attributes->get('requested_fields', []);

        if (isset($requestedFields['jobs'])
            && ! in_array($key, explode(',', $requestedFields['jobs']), true)) {
            return Optional::create();
        }

        return array_key_exists($key, $job->getAttributes())
            ? $job->{$key}
            : Optional::create();
    }
}
