<?php

namespace App\Data;

use App\Enums\CompanySize;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CompanyData extends Data
{
    /**
     * @param array<JobData> $jobs
     */
    public function __construct(
        public int $id,
        public string $name,
        /** The list of opened jobs of this company. */
        public array $jobs,
        public Optional|ContactData $contact,
        public CompanySize $size,
    )
    {
    }
}
