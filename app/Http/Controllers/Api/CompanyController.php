<?php

namespace App\Http\Controllers\Api;

use App\Data\CompanyData;
use App\Data\CompanyPayloadData;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    /**
     * List companies.
     *
     * List all the companies which looking for the applicants. With the ability to filter by name or creation date.
     */
    public function index(Request $request)
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFields(['id', 'name', 'jobs.id', 'jobs.title', 'created_at'])
            ->allowedFilters([
                /**
                 * The name to filter a company by. Multiple values can be passed, separated via `,` (`Nike,Tesla`)
                 * @example Tesla
                 */
                'name',
                /**
                 * The date of the creation of the company.
                 * @format date
                 */
                'created_at',
            ])
            ->allowedIncludes('jobs')
            ->allowedSorts([
                'name',
                'created_at',
            ])
            ->paginate($request->integer('per_page', 15));

        return CompanyData::collect($companies, PaginatedDataCollection::class);
    }

    /**
     * Create company.
     */
    public function store(CompanyPayloadData $companyData)
    {
        $company = Company::create($companyData->toArray());

        /** @status 201 */
        return CompanyData::from($company);
    }

    /**
     * Get company.
     */
    public function show(Company $company)
    {
        return CompanyData::from($company);
    }

    /**
     * Delete company.
     */
    public function destroy(Company $company)
    {
        abort_if(
            $company->hasOpenedJobPositions(),
            403,
            'Cannot delete a job when there are opened job positions',
        );

        $company->delete();

        return response()->noContent();
    }
}
