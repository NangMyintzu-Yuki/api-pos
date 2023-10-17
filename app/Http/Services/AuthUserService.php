<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthUserService extends BaseController{
    public function __construct(private User $user)
    {

    }
    public function register(array $request)
    {

        $request['password'] = Hash::make($request['password']);
        $request['created_at'] = Carbon::now();
        DB::table('users')->insert($request);
        return $this->sendResponse('Register Success');
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
        info([$token]);
        $getToken = strpos($token,"|");
        $finalToken = [
            'loginInfo' => $loginInfo,
            'token' => substr($token,$getToken + 1)
        ];
        info($finalToken);
        return $this->sendResponse("User Login Success",$finalToken);
    }

    public function logout($request)
    {
        // return $request->user()->currentAccessToken();

        // posUserToken
        // remove token
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse("Logout Success");
    }
}
