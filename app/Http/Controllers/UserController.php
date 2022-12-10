<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Traits\MyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use MyResponse;
    public function login(UserLoginRequest $request)
    {
        $user = User::where('name', $request->name)->first();
        if (!isset($user)) {
            return $this->returnError('المستخدم غير موجود', 300);
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->returnError('البيانات غير متطابقة', 301);
        }
        $token = $user->createToken('auth')->plainTextToken;
        return $this->returnData('token', $token);
    }
    public function logout(Request $request)
    {
      /*   Auth::user()->tokens->each(function ($token, $key) use ($request) {
            if ($token != $request->user()->currentAccessToken())
                $token->delete();
        }); */
        $request->user()->currentAccessToken()->delete();
        return $this->returnMessage("logout sucessfully.");
    }
}
