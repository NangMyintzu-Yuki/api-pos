<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUserService extends BaseController{
    public function __construct(private User $user)
    {

    }
    public function register($request)
    {
        return "User Register";
    }
    public function login(array $request)
    {
        $loginInfo = $this->user->where('phone_no',$request['phone_no'])->first();
        if(!$loginInfo || !Hash::check($request['password'], $loginInfo->password))
        {
            return $this->sendError('Phone No or password does not match');
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
