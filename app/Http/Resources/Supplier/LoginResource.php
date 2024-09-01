<?php

namespace App\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $tokenExpiration = now()->addMinutes(config('sanctum.expiration'));
        $supplier = auth()->guard('supplier')->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $supplier->createToken('supplier-token', ['*'], $tokenExpiration)->plainTextToken,
            'permissions' => !empty($this->permissions) ? $this->permissions->pluck('name') : [],
            'token_iat' => now()->toDateTimeString(),
            'token_exp' => $tokenExpiration->toDateTimeString(),
        ];
    }
}
