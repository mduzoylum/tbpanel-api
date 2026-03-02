<?php

namespace App\Http\Controllers\Admin;

use App\Data\Auth\ForgotPasswordData;
use App\Data\Auth\LoginData;
use App\Data\Auth\ResetPasswordData;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Models\User;
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
    #[Endpoint(title: 'Admin Login', description: 'Admin kullanicisini giris yaptirir ve access token doner.')]
    #[BodyParameter('email', 'Admin e-posta adresi', required: true, type: 'string', format: 'email', example: 'admin@example.com')]
    #[BodyParameter('password', 'Sifre', required: true, type: 'string', format: 'password', example: 'secret123')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,token:string,permissions:string[],token_iat:string,token_exp:string|null}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Hatali kimlik bilgisi', examples: ['message' => 'Invalid credentials!'])]
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

    #[Endpoint(title: 'Admin Me', description: 'Token sahibi admin kullanicisinin profil bilgisini doner.')]
    #[Response(status: 200, type: 'array{id:int,name:string,email:string,permissions:string[]}')]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function me(Request $request): array
    {
        if (! $request->user() instanceof User) {
            throw new UnauthorizedException('Unauthorized');
        }

        /** @var User $user */
        $user = $request->user();

        return [
            'id' => (int) $user->id,
            'name' => (string) ($user->name ?? ''),
            'email' => (string) ($user->email ?? ''),
            'permissions' => $this->resolvePermissions($user),
        ];
    }

    #[Endpoint(title: 'Admin Logout', description: 'Mevcut access tokeni iptal eder.')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Basariyla cikis yaptiniz.'])]
    #[Response(status: 401, type: 'array{message:string}', description: 'Yetkisiz istek', examples: ['message' => 'Unauthorized'])]
    public function logout(Request $request): array
    {
        if (! $request->user() instanceof User) {
            throw new UnauthorizedException('Unauthorized');
        }

        $request->user()->currentAccessToken()?->delete();

        return ['message' => __('auth.logout_success')];
    }

    #[Endpoint(title: 'Admin Forgot Password', description: 'Admin kullanici icin sifre sifirlama baglantisi gonderir.')]
    #[BodyParameter('email', 'Admin e-posta adresi', required: true, type: 'string', format: 'email', example: 'admin@example.com')]
    #[Response(status: 200, type: 'array{message:string}', examples: ['message' => 'Sifre sifirlama baglantisi e-posta adresinize gonderildi.'])]
    #[Response(status: 422, type: 'array{message:string,errors:array<string,string[]>}', description: 'Validation hatasi')]
    public function forgotPassword(ForgotPasswordData $data): array
    {
        $payload = ['email' => $data->email];

        $status = Password::broker('users')->sendResetLink($payload);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new UnauthorizedException(__($status));
        }

        return ['message' => __('auth.password_reset_link_sent')];
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
        $payload = $data->toArray();

        $status = Password::broker('users')->reset(
            $payload,
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

    private function buildTokenPayload(User $user, string $tokenName): array
    {
        if (! in_array(HasApiTokens::class, class_uses_recursive($user), true)) {
            throw new \RuntimeException('User model must use HasApiTokens trait.');
        }

        $issuedAt = now();
        $expiration = config('sanctum.expiration');
        $expiresAt = is_numeric($expiration) ? now()->addMinutes((int) $expiration) : null;

        return [
            'id' => (int) $user->id,
            'name' => (string) ($user->name ?? ''),
            'email' => (string) ($user->email ?? ''),
            'token' => $user->createToken($tokenName, ['*'], $expiresAt)->plainTextToken,
            'permissions' => $this->resolvePermissions($user),
            'token_iat' => $issuedAt->toDateTimeString(),
            'token_exp' => $expiresAt?->toDateTimeString(),
        ];
    }

    private function resolvePermissions(User $user): array
    {
        if (method_exists($user, 'getPermissionNames')) {
            return $user->getPermissionNames()->values()->toArray();
        }

        if (method_exists($user, 'permissions')) {
            return $user->permissions->pluck('name')->values()->toArray();
        }

        return [];
    }
}
