<?php
namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;
use Illuminate\Support\Facades\Hash;

class AuthOperatorService extends Controller{
    public function __construct(private Operator $operator)
    {
    }
    public function register($request)
    {
        return "Hello";
    }
    public function login($request)
    {
        $loginInfo = $this->operator->where('username', $request['username'])->first();
        if (!$loginInfo || !Hash::check($request['password'], $loginInfo->password)) {
            return $this->sendError("Username or password does not match");
        }
        $loginInfo = new OperatorResource($loginInfo);
        $token = $loginInfo->createToken('posOperatorToken')->plainTextToken;
        $getToken = strpos($token,"|");
        $finalToken = [
            'loginInfo' =>$loginInfo,
            'token' => substr($token,$getToken + 1)
        ];
        return $this->sendResponse("Login Success",$finalToken);
    }

}
