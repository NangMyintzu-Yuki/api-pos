<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserChangeStatusRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {

    }
    public function index(Request $request)
    {
        return $this->userService->index($request);
    }
    public function store(StoreUserRequest $request)
    {
        return $this->userService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->userService->edit($request);
    }
    public function update(UpdateUserRequest $request)
    {
        return $this->userService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->userService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->userService->filterUser($request);
    }
    public function changePassword(Request $request)
    {
        return $this->userService->changePassword($request);
    }
    public function resetPassword(Request $request)
    {
        return $this->userService->resetPassword($request);
    }
    public function changeStatus(UserChangeStatusRequest $request)
    {
        return $this->userService->changeStatus($request->validated());
    }

}
