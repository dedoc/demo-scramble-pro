<?php

namespace Tests\Feature;

use App\Data\JobData;
use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_a_queryable_demo_dataset(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'demo@example.com']);
        $this->assertDatabaseCount('companies', 3);
        $this->assertDatabaseCount('job_positions', 4);
        $this->assertDatabaseCount('candidates', 5);
        $this->assertDatabaseCount('job_applications', 7);
        $this->assertSame(3, Job::where('status', JobStatus::OPEN)->count());
    }

    public function test_protected_endpoints_require_a_token(): void
    {
        $this->getJson('/api/company')->assertUnauthorized();
        $this->postJson('/api/company', ['name' => 'Example'])->assertUnauthorized();
        $this->postJson('/api/job', [])->assertUnauthorized();
    }

    public function test_company_endpoints_list_filter_include_create_and_show_companies(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $acme = Company::factory()->create(['name' => 'Acme Analytics']);
        Company::factory()->create(['name' => 'Northstar Labs']);
        Job::factory()->for($acme)->create(['title' => 'Backend Engineer']);

        $this->getJson('/api/company?include=jobs&filter[name]=Acme')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Acme Analytics')
            ->assertJsonPath('data.0.jobs.0.title', 'Backend Engineer');

        $createResponse = $this->postJson('/api/company', ['name' => 'New Company'])
            ->assertCreated()
            ->assertJsonPath('name', 'New Company');

        $this->getJson('/api/company/'.$createResponse->json('id'))
            ->assertOk()
            ->assertJsonPath('name', 'New Company');
    }

    public function test_company_list_supports_sparse_fields(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $company = Company::factory()->create(['name' => 'Acme Analytics']);

        $this->getJson('/api/company?fields[companies]=id')
            ->assertOk()
            ->assertJsonPath('data.0', ['id' => $company->id]);

        $this->getJson('/api/company?fields[companies]=name,created_at')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Acme Analytics')
            ->assertJsonMissingPath('data.0.id')
            ->assertJsonStructure(['data' => [['name', 'created_at']]]);
    }

    public function test_company_list_supports_sparse_fields_on_included_jobs(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $company = Company::factory()->create();
        $job = Job::factory()->for($company)->create(['title' => 'Backend Engineer']);

        $this->getJson('/api/company?include=jobs&fields[companies]=id&fields[jobs]=id,title')
            ->assertOk()
            ->assertJsonPath('data.0', [
                'id' => $company->id,
                'jobs' => [[
                    'id' => $job->id,
                    'title' => 'Backend Engineer',
                ]],
            ]);
    }

    public function test_job_endpoint_creates_a_job_for_the_payload_company(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $company = Company::factory()->create();

        $this->postJson('/api/job', [
            'title' => 'Platform Engineer',
            'status' => JobStatus::OPEN->value,
            'salary_range' => [100_000, 140_000],
            'company_id' => $company->id,
        ])
            ->assertCreated()
            ->assertJsonPath('title', 'Platform Engineer')
            ->assertJsonPath('salary_range.1', 140_000);

        $this->assertDatabaseHas('job_positions', [
            'company_id' => $company->id,
            'title' => 'Platform Engineer',
        ]);
    }

    public function test_company_with_an_open_job_cannot_be_deleted(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $company = Company::factory()->create();
        Job::factory()->for($company)->create(['status' => JobStatus::OPEN]);

        $this->deleteJson('/api/company/'.$company->id)
            ->assertForbidden()
            ->assertJsonPath('message', 'Cannot delete a company when there are open job positions');
    }

    public function test_company_and_its_closed_jobs_can_be_deleted(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $company = Company::factory()->create();
        $job = Job::factory()->for($company)->create(['status' => JobStatus::CLOSED]);

        $this->deleteJson('/api/company/'.$company->id)->assertNoContent();

        $this->assertModelMissing($company);
        $this->assertModelMissing($job);
    }

    public function test_public_statistics_count_only_open_jobs(): void
    {
        Candidate::factory()->count(4)->create();
        Job::factory()->count(2)->create(['status' => JobStatus::OPEN]);
        Job::factory()->create(['status' => JobStatus::CLOSED]);

        $this->getJson('/api/statistics')
            ->assertOk()
            ->assertExactJson([
                'candidates' => 4,
                'open_jobs' => 2,
            ]);
    }

    public function test_job_statistics_are_calculated_from_applications(): void
    {
        $job = Job::factory()->create();
        JobApplication::factory()->count(2)->for($job, 'job')->create([
            'status' => ApplicationStatus::PENDING,
        ]);
        JobApplication::factory()->for($job, 'job')->create([
            'status' => ApplicationStatus::REJECTED,
        ]);
        JobApplication::factory()->for($job, 'job')->create([
            'status' => ApplicationStatus::APPROVED,
        ]);

        $data = JobData::from($job)->include('statistics')->toArray();

        $this->assertSame([
            'applications' => 4,
            'rejections' => 1,
            'approved' => 1,
        ], $data['statistics']);
    }
}
