<?php

namespace Database\Seeders;

use App\Enums\CompanySize;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Acme Analytics',
                'size' => CompanySize::MEDIUM,
                'contact' => ['name' => 'Maya Chen', 'email' => 'maya@acme.example'],
            ],
            [
                'name' => 'Northstar Labs',
                'size' => CompanySize::SMALL,
                'contact' => ['name' => 'Theo Martin', 'email' => 'theo@northstar.example'],
            ],
            [
                'name' => 'Juniper Works',
                'size' => CompanySize::LARGE,
                'contact' => ['name' => 'Ari Jones', 'email' => 'ari@juniper.example'],
            ],
        ] as $company) {
            Company::updateOrCreate(['name' => $company['name']], $company);
        }
    }
}
