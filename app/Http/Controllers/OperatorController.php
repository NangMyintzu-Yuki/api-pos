<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorChangePasswordRequest;
use App\Http\Requests\StoreOperatorRequest;
use App\Http\Requests\UpdateOperatorRequest;
use App\Http\Services\OperatorService;
use Illuminate\Http\Request;

class OperatorController extends BaseController
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
    public function store(StoreOperatorRequest $request)
    {
        return $this->operatorService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->operatorService->edit($request);
    }
    public function update(UpdateOperatorRequest $request)
    {
        return $this->operatorService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->operatorService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->operatorService->filterOperator($request);
    }
    public function changePassword(Request $request)
    {
        // $request->validate([
        //     'operator_id' => 'required',
        //     'new_password' => 'required',
        //     'old_password' => 'required',
        // ]);

        // $this->articleService->store($validatedData);
        // return $this->operatorService->changePassword($request->only('operator_id','new_password','old_password'));
        return $this->operatorService->changePassword($request);
        // return "Change Passwrod";
    }
}
