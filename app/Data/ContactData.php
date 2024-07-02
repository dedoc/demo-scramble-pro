<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class ContactData extends Data
{
    public function __construct(
        public string $name,
        #[Email]
        public string $email,
    )
    {
    }
}
