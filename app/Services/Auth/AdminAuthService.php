<?php

namespace App\Services\Auth;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminAuthService extends AbstractAuthService
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

        if (! auth()->guard('admin')->attempt($credentials)) {
            throw new UnauthorizedException('Invalid credentials!');
        }

        /** @var User $user */
        $user = auth()->guard('admin')->user();

        return $this->buildTokenPayload($user, (string) config('app.hash_token', 'admin-token'));
    }

    public function me(User $user): array
    {
        return $this->profilePayload($user);
    }

    public function logout(User $user): array
    {
        $this->revokeCurrentToken($user);

        return ['message' => __('auth.logout_success')];
    }

    /**
     * @throws UnauthorizedException
     */
    public function forgotPassword(ForgotPasswordData $data): array
    {
        $status = Password::broker('users')->sendResetLink(['email' => $data->email]);

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
        $status = Password::broker('users')->reset(
            $data->toArray(),
            function ($user, $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new UnauthorizedException(__($status));
        }

        return ['message' => __('auth.reset_password_success')];
    }
}
