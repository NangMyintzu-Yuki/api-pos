<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthOperatorRequest;
use App\Http\Services\AuthOperatorService;
use Illuminate\Http\Request;

class AuthOperatorController extends Controller
{
    public function __construct(private AuthOperatorService $authOperatorService)
    {

    }

    public function login(AuthOperatorRequest $request)
    {
        return $this->authOperatorService->login($request->validated());
    }

    public function register(Request $request)
    {
        return $this->authOperatorService->register($request);
    }

    public function logout(Request $request)
    {
        return $this->authOperatorService->logout($request);
    }
}
