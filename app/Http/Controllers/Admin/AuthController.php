<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function resetPassword(ResetPasswordRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        $user->sendPasswordResetNotification($user->createToken(config('app.hash_token'), ['*'], now()->addMinutes(config('sanctum.expiration'))->plainTextToken));

        return $this->successResponse('Password reset email sent!');
    }
}
