<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthUserService;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function __construct(private AuthUserService $authUserService)
    {
    }
    public function login(Request $request)
    {
        return $this->authUserService->login($request);
    }
    public function register(Request $request)
    {
        return $this->authUserService->register($request);
    }
}
