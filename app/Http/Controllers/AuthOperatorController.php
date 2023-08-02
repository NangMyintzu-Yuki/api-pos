<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthOperatorService;
use Illuminate\Http\Request;

class AuthOperatorController extends Controller
{
    public function __construct(private AuthOperatorService $authService)
    {

    }
    public function login(Request $request)
    {
        return $this->authService->login($request);
    }
    public function register(Request $request)
    {
        return $this->authService->register($request);
    }

}
