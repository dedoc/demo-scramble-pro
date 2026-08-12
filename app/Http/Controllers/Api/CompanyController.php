<?php

namespace App\Http\Controllers\Api;

use App\Data\CompanyData;
use App\Data\CompanyListData;
use App\Data\CompanyPayloadData;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Dedoc\Scramble\Attributes\QueryParameter;
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
    #[QueryParameter('filter[name]', 'The name to filter a company by. Multiple values can be passed, separated via `,` (`Nike,Tesla`)', example: 'Tesla')]
    #[QueryParameter('filter[created_at]', 'The date of the creation of the company.', format: 'date')]
    public function index(Request $request)
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFields('id', 'name', 'jobs.id', 'jobs.title', 'jobs.company_id', 'created_at')
            ->allowedFilters(
                'name',
                'created_at',
            )
            ->allowedIncludes('jobs')
            ->allowedSorts(
                'name',
                'created_at',
            )
            ->jsonPaginate();

        return CompanyListData::collect($companies, PaginatedDataCollection::class);
    }

    /**
     * Create company.
     */
    public function store(CompanyPayloadData $companyData)
    {
        $company = Company::create($companyData->toArray());

        return response()->json(CompanyData::from($company), 201);
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
            'Cannot delete a company when there are open job positions',
        );

        $company->jobs()->delete();
        $company->delete();

        return response()->noContent();
    }
}
