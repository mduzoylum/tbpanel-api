<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return $this->successResponse('Login successful!', ['token' => auth()->user()->createToken(config('app.tb_token'))->plainTextToken]);
        }

        throw new UnauthorizedException('Invalid credentials!');
    }
}
