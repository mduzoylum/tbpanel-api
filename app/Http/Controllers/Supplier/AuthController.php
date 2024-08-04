<?php

namespace App\Http\Controllers\Supplier;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use App\Services\Integrations\IntegrationProviderFactory;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
          $credentials = $request->only('email', 'password');

        if (auth()->guard('supplier')->attempt($credentials)) {
            return new LoginResource(auth()->guard('supplier')->user());
        }

        throw new UnauthorizedException('Invalid credentials!');
    }

    public function forgotPassword(Request $request)
    {
        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $this->successResponse(__('auth.password_reset_link_sent'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $status = Password::broker('users')->reset(
            $request->only('email','password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new UnauthorizedException(__($status));
        }

        return $this->successResponse(__("auth.reset_password_success"));
    }
}
