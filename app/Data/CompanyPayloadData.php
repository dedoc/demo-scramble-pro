<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CompanyPayloadData extends Data
{
    public function __construct(
        public string $name,
    )
    {
    }
}
