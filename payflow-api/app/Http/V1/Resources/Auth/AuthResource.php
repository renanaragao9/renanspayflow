<?php

namespace App\Http\V1\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address,
            'profile_photo_url' => $this->profile_photo_url,
            'email_verified_at' => $this->email_verified_at,
            'last_login_at' => $this->last_login_at,
            'login_count' => $this->login_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
