<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUserService extends Controller{
    public function __construct(private User $user)
    {

    }
    public function register($request)
    {
        return "User Register";
    }
    public function login($request)
    {
        $loginInfo = $this->user->where('username',$request['username'])->first();
        if(!$loginInfo || !Hash::check($request['password'], $loginInfo->password))
        {
            return $this->sendError('Username or password does not match');
        }
        $loginInfo = new UserResource($loginInfo);
        $token = $loginInfo->createToken('posUserToken')->plainTextToken;
        $getToken = strpos($token,"|");
        $finalToken = [
            'loginInfo' => $loginInfo,
            'token' => substr($token,$getToken + 1)
        ];
        return $this->sendResponse("User Login Success",$finalToken);
    }
}
