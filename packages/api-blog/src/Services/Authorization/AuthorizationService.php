<?php

namespace Admin\ApiBolg\Services\Authorization;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorizationService
{
    public function register($input): User
    {
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
    }


}
