<?php

namespace App\Data;

use App\Enums\JobStatus;
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
    )
    {
    }
}
