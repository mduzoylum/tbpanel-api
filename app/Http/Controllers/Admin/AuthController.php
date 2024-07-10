<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
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

        if (auth()->attempt($credentials)) {
            $data = auth()->user()->only('id', 'name', 'surname', 'email');
            $data['token'] = auth()->user()->createToken(config('app.hash_token'), ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
            $data['permissions'] = !empty(auth()->user()->permissions) ? auth()->user()->permissions->pluck('name') : [];
            $data['token_iat'] = now()->toDateTimeString();
            $data['token_exp'] = now()->addMinutes(config('sanctum.expiration'))->toDateTimeString();

            return $this->successResponse('Login successful!', $data);
        }

        throw new UnauthorizedException('Invalid credentials!');
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new UnauthorizedException('User not found!');
        }

        $user->sendPasswordResetNotification(Password::broker('users')->createToken($user));

        return $this->successResponse(__('auth.password_reset_link_sent'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->successResponse(__("auth.reset_password_token_error"));
        }

        return $this->successResponse(__("auth.password_reset_success"));
    }
}
