<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class ForgotPasswordData extends Data
{
    public function __construct(
        #[Email]
        public string $email,
    ) {}
}
