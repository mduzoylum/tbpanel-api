<?php

namespace App\Http\Controllers\Supplier;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    #[Endpoint(title: 'Supplier Login', description: 'Tedarikci kullanicisini giris yaptirir ve access token doner.')]
    #[BodyParameter('email', 'Tedarikci e-posta adresi', required: true, type: 'string', format: 'email', example: 'supplier@example.com')]
    #[BodyParameter('password', 'Sifre', required: true, type: 'string', format: 'password', example: 'secret123')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,token:string,permissions:string[],token_iat:string,token_exp:string|null}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Hatali kimlik bilgisi', examples: ['message' => 'Invalid credentials!'])]
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

    #[Endpoint(title: 'Supplier Me', description: 'Token sahibi tedarikcinin profil bilgisini doner.')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,permissions:string[]}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function me(Request $request): array
    {
        if (! $request->user() instanceof Supplier) {
            throw new UnauthorizedException('Unauthorized');
        }

        /** @var Supplier $supplier */
        $supplier = $request->user();

        return [
            'id' => (int) $supplier->id,
            'name' => (string) ($supplier->name ?? ''),
            'email' => (string) ($supplier->email ?? ''),
            'permissions' => $this->resolvePermissions($supplier),
        ];
    }

    #[Endpoint(title: 'Supplier Logout', description: 'Mevcut access tokeni iptal eder.')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Basariyla cikis yaptiniz.'])]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function logout(Request $request): array
    {
        if (! $request->user() instanceof Supplier) {
            throw new UnauthorizedException('Unauthorized');
        }

        $request->user()->currentAccessToken()?->delete();

        return ['message' => __('auth.logout_success')];
    }

    #[Endpoint(title: 'Supplier Forgot Password', description: 'Tedarikci kullanici icin sifre sifirlama baglantisi gonderir.')]
    #[BodyParameter('email', 'Tedarikci e-posta adresi', required: true, type: 'string', format: 'email', example: 'supplier@example.com')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Sifre sifirlama baglantisi e-posta adresinize gonderildi.'])]
    #[Response(status: 422, type: 'array{message:string,errors:array<string,string[]>}', description: 'Validation hatasi')]
    public function forgotPassword(ForgotPasswordData $data): array
    {
        $payload = ['email' => $data->email];

        $status = Password::broker('suppliers')->sendResetLink($payload);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new UnauthorizedException(__($status));
        }

        return ['message' => __('auth.password_reset_link_sent')];
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
        $payload = $data->toArray();

        $status = Password::broker('suppliers')->reset(
            $payload,
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

    private function buildTokenPayload(Supplier $supplier, string $tokenName): array
    {
        if (! in_array(HasApiTokens::class, class_uses_recursive($supplier), true)) {
            throw new \RuntimeException('Supplier model must use HasApiTokens trait.');
        }

        $issuedAt = now();
        $expiration = config('sanctum.expiration');
        $expiresAt = is_numeric($expiration) ? now()->addMinutes((int) $expiration) : null;

        return [
            'id' => (int) $supplier->id,
            'name' => (string) ($supplier->name ?? ''),
            'email' => (string) ($supplier->email ?? ''),
            'token' => $supplier->createToken($tokenName, ['*'], $expiresAt)->plainTextToken,
            'permissions' => $this->resolvePermissions($supplier),
            'token_iat' => $issuedAt->toDateTimeString(),
            'token_exp' => $expiresAt?->toDateTimeString(),
        ];
    }

    private function resolvePermissions(Supplier $supplier): array
    {
        if (method_exists($supplier, 'getPermissionNames')) {
            return $supplier->getPermissionNames()->values()->toArray();
        }

        if (method_exists($supplier, 'permissions')) {
            return $supplier->permissions->pluck('name')->values()->toArray();
        }

        return [];
    }
}
