<?php

namespace App\Http\Controllers\Api;

use App\Data\CompanyData;
use App\Data\CompanyPayloadData;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class CompanyController extends Controller
{
    /**
     * List companies.
     */
    public function index(Request $request)
    {
        $companies = Company::query()
            ->when(
                /** Search string for companies. Will search companies by `name` attribute. */
                $request->get('q'),
                fn ($q, $searchQuery) => $q->where('name', 'like', "%$searchQuery%")
            )
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
