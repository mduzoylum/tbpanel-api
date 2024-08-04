<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->createToken(config('app.hash_token'), ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken,
            'permissions' => !empty($this->permissions) ? $this->permissions->pluck('name') : [],
            'token_iat' => now()->toDateTimeString(),
            'token_exp' => now()->addMinutes(config('sanctum.expiration'))->toDateTimeString(),
        ];


    }
}
