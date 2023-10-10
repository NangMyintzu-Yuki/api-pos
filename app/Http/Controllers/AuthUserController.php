<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthUserRegisterRequest;
use App\Http\Requests\AuthUserRequest;
use App\Http\Services\AuthUserService;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function __construct(private AuthUserService $authUserService)
    {
    }
    public function login(AuthUserRequest $request)
    {
        return $this->authUserService->login($request->validated());
    }
    public function register(AuthUserRegisterRequest $request)
    {
        return $this->authUserService->register($request->validated());
    }
    public function logout(Request $request)
    {
        return $this->authUserService->logout($request);
    }
}
