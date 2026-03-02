<?php

namespace App\Http\Controllers\Admin;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AdminAuthService;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AdminAuthService $authService
    ) {}

    #[Endpoint(title: 'Admin Login', description: 'Admin kullanicisini giris yaptirir ve access token doner.')]
    #[BodyParameter('email', 'Admin e-posta adresi', required: true, type: 'string', format: 'email', example: 'admin@example.com')]
    #[BodyParameter('password', 'Sifre', required: true, type: 'string', format: 'password', example: 'secret123')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,token:string,permissions:string[],token_iat:string,token_exp:string|null}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Hatali kimlik bilgisi', examples: ['message' => 'Invalid credentials!'])]
    public function login(LoginData $data): array
    {
        return $this->authService->login($data);
    }

    #[Endpoint(title: 'Admin Me', description: 'Token sahibi admin kullanicisinin profil bilgisini doner.')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,permissions:string[]}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function me(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        return $this->authService->me($user);
    }

    #[Endpoint(title: 'Admin Logout', description: 'Mevcut access tokeni iptal eder.')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Basariyla cikis yaptiniz.'])]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function logout(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        return $this->authService->logout($user);
    }

    #[Endpoint(title: 'Admin Forgot Password', description: 'Admin kullanici icin sifre sifirlama baglantisi gonderir.')]
    #[BodyParameter('email', 'Admin e-posta adresi', required: true, type: 'string', format: 'email', example: 'admin@example.com')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Sifre sifirlama baglantisi e-posta adresinize gonderildi.'])]
    #[Response(status: 422, type: 'array{message:string,errors:array<string,string[]>}', description: 'Validation hatasi')]
    public function forgotPassword(ForgotPasswordData $data): array
    {
        return $this->authService->forgotPassword($data);
    }

    #[Endpoint(title: 'Admin Reset Password', description: 'Token ile admin sifresini yeniler.')]
    #[BodyParameter('email', 'Admin e-posta adresi', required: true, type: 'string', format: 'email', example: 'admin@example.com')]
    #[BodyParameter('token', 'Sifre sifirlama tokeni', required: true, type: 'string', example: 'reset-token')]
    #[BodyParameter('password', 'Yeni sifre', required: true, type: 'string', format: 'password', example: 'newSecret123')]
    #[BodyParameter('password_confirmation', 'Yeni sifre (tekrar)', required: true, type: 'string', format: 'password', example: 'newSecret123')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Sifreniz basariyla guncellendi.'])]
    #[Response(status: 422, type: 'array{message:string,errors:array<string,string[]>}', description: 'Validation hatasi')]
    public function resetPassword(ResetPasswordData $data): array
    {
        return $this->authService->resetPassword($data);
    }
}
