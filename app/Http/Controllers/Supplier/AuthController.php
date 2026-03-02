<?php

namespace App\Http\Controllers\Supplier;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Services\Auth\SupplierAuthService;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly SupplierAuthService $authService
    ) {}

    #[Endpoint(title: 'Supplier Login', description: 'Tedarikci kullanicisini giris yaptirir ve access token doner.')]
    #[BodyParameter('email', 'Tedarikci e-posta adresi', required: true, type: 'string', format: 'email', example: 'supplier@example.com')]
    #[BodyParameter('password', 'Sifre', required: true, type: 'string', format: 'password', example: 'secret123')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,token:string,permissions:string[],token_iat:string,token_exp:string|null}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Hatali kimlik bilgisi', examples: ['message' => 'Invalid credentials!'])]
    public function login(LoginData $data): array
    {
        return $this->authService->login($data);
    }

    #[Endpoint(title: 'Supplier Me', description: 'Token sahibi tedarikcinin profil bilgisini doner.')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,permissions:string[]}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function me(Request $request): array
    {
        /** @var Supplier $supplier */
        $supplier = $request->user();

        return $this->authService->me($supplier);
    }

    #[Endpoint(title: 'Supplier Logout', description: 'Mevcut access tokeni iptal eder.')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Basariyla cikis yaptiniz.'])]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function logout(Request $request): array
    {
        /** @var Supplier $supplier */
        $supplier = $request->user();

        return $this->authService->logout($supplier);
    }

    #[Endpoint(title: 'Supplier Forgot Password', description: 'Tedarikci kullanici icin sifre sifirlama baglantisi gonderir.')]
    #[BodyParameter('email', 'Tedarikci e-posta adresi', required: true, type: 'string', format: 'email', example: 'supplier@example.com')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Sifre sifirlama baglantisi e-posta adresinize gonderildi.'])]
    #[Response(status: 422, type: 'array{message:string,errors:array<string,string[]>}', description: 'Validation hatasi')]
    public function forgotPassword(ForgotPasswordData $data): array
    {
        return $this->authService->forgotPassword($data);
    }

    #[Endpoint(title: 'Supplier Reset Password', description: 'Token ile tedarikci sifresini yeniler.')]
    #[BodyParameter('email', 'Tedarikci e-posta adresi', required: true, type: 'string', format: 'email', example: 'supplier@example.com')]
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
