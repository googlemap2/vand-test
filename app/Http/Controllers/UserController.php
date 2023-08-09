<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ValidatesRequests;
    public function signUp(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'required|'
        ]);
    }
}
