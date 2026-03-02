<?php

namespace App\Services\Auth;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Exceptions\UnauthorizedException;
use App\Models\Supplier;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class SupplierAuthService extends AbstractAuthService
{
    /**
     * @throws UnauthorizedException
     */
    public function login(LoginData $data): array
    {
        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
        ];

        if (! auth()->guard('supplier')->attempt($credentials)) {
            throw new UnauthorizedException('Invalid credentials!');
        }

        /** @var Supplier $supplier */
        $supplier = auth()->guard('supplier')->user();

        return $this->buildTokenPayload($supplier, 'supplier-token');
    }

    public function me(Supplier $supplier): array
    {
        return $this->profilePayload($supplier);
    }

    public function logout(Supplier $supplier): array
    {
        $this->revokeCurrentToken($supplier);

        return ['message' => __('auth.logout_success')];
    }

    /**
     * @throws UnauthorizedException
     */
    public function forgotPassword(ForgotPasswordData $data): array
    {
        $status = Password::broker('suppliers')->sendResetLink(['email' => $data->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new UnauthorizedException(__($status));
        }

        return ['message' => __('auth.password_reset_link_sent')];
    }

    /**
     * @throws UnauthorizedException
     */
    public function resetPassword(ResetPasswordData $data): array
    {
        $status = Password::broker('suppliers')->reset(
            $data->toArray(),
            function ($supplier, $password): void {
                $supplier->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $supplier->save();
                event(new PasswordReset($supplier));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new UnauthorizedException(__($status));
        }

        return ['message' => __('auth.reset_password_success')];
    }
}
