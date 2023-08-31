<?php

namespace App\Http\Controllers;

use App\Http\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService)
    {
    }
    public function index(Request $request)
    {
        return $this->roleService->index($request);
    }
}
