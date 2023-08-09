<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  protected $userServce;
  public function __construct(UserService $userServce)
  {
    $this->userServce = $userServce;
  }
  public function signup(Request $request)
  {
    $body = $request->getContent();
    $body = json_decode($body, true);
    $validator = Validator::make($request->all(), [
      'user_name' => 'required|max:255|unique:users',
      'password' => 'required',
      'email' => 'required|unique:users'
    ]);
    if ($validator->fails()) {
      return response()->json([
        'message' => implode(', ', $validator->errors()->all())
      ], 422);
    }
    $this->userServce->signup($body);
    return response()->json(['message' => 'Register Sucessed!!']);
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required',
      'email' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json([
        'message' => implode(', ', $validator->errors()->all())
      ], 422);
    }
    $body = $request->getContent();
    $body = json_decode($body, true);
    $token = Auth::attempt(['email' => $body['email'], 'password' => $body['password'], 'deleted' => false]);
    if (!$token) {
      return response('Unauthorized', 401);
    }
    return $this->userServce->createNewToken($token);
  }
  public function logout()
  {
    return $this->userServce->logout();
  }
  public function refresh()
  {
    return $this->userServce->refeshToken();
  }
}
