<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class ResetPasswordData extends Data
{
    public function __construct(
        #[Email]
        public string $email,
        public string $token,
        #[Confirmed, Min(8)]
        public string $password,
        public string $password_confirmation,
    ) {}
}
