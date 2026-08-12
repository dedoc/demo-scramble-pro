<?php

namespace App\Data;

use App\Enums\CompanySize;
use App\Models\Company;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;

class CompanyData extends Data
{
    /**
     * @param  array<JobData>  $jobs
     */
    public function __construct(
        public int $id,
        public string $name,
        /** The list of opened jobs of this company. */
        public Lazy|array $jobs,
        public Optional|ContactData|null $contact,
        public CompanySize $size,
    ) {}

    public static function fromModel(Company $company): self
    {
        return new self(
            id: $company->id,
            name: $company->name,
            jobs: Lazy::whenLoaded(
                'jobs',
                $company,
                fn () => JobData::collect($company->jobs)->all(),
            ),
            contact: $company->contact,
            size: $company->size,
        );
    }
}
