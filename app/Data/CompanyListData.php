<?php

namespace App\Data;

use App\Enums\CompanySize;
use App\Models\Company;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CompanyListData extends Data
{
    /**
     * @param  array<JobListData>  $jobs
     */
    public function __construct(
        public int|Optional $id,
        public string|Optional $name,
        /** The jobs included with this company. */
        public Lazy|array $jobs,
        public ContactData|null|Optional $contact,
        public CompanySize|Optional $size,
        /** @format date-time */
        public string|Optional $createdAt,
    ) {}

    public static function fromModel(Company $company): self
    {
        return new self(
            id: self::attribute($company, 'id'),
            name: self::attribute($company, 'name'),
            jobs: Lazy::whenLoaded(
                'jobs',
                $company,
                fn () => JobListData::collect($company->jobs)->all(),
            ),
            contact: self::attribute($company, 'contact'),
            size: self::attribute($company, 'size'),
            createdAt: $company->getAttributes()['created_at'] ?? Optional::create(),
        );
    }

    private static function attribute(Company $company, string $key): mixed
    {
        $requestedFields = request()->attributes->get('requested_fields', []);

        if (isset($requestedFields['companies'])
            && ! in_array($key, explode(',', $requestedFields['companies']), true)) {
            return Optional::create();
        }

        return array_key_exists($key, $company->getAttributes())
            ? $company->{$key}
            : Optional::create();
    }
}
