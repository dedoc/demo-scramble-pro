<?php

namespace App\Data;

use App\Enums\JobStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class JobPayloadData extends Data
{
    public function __construct(
        public string $title,
        public JobStatus $status,
        /** @var array{int, int} */
        public array $salaryRange,
        /** Company this job should be associated with. */
        #[Exists('companies', 'id')]
        public int $companyId,
    )
    {
    }
}
