<?php

namespace App\Http\Controllers;

use App\Http\Services\OperatorService;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function __construct(private OperatorService $operatorService)
    {

    }
    public function index(Request $request)
    {
        return $this->operatorService->index($request);
    }
    public function create()
    {
        return $this->operatorService->create();
    }
}
