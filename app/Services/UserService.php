<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class  UserService
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function signup($data)
    {
        $data['password'] = Hash::make($data['password']);
        $this->user->fill($data);
        $this->user->save();
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'User successfully signed out'
        ]);
    }

    public function refreshToken()
    {
        return response()->json([
            'user' => auth()->user(),
            'authorisation' => [
                'token' => auth()->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
