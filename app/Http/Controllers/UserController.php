<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserResetPasswordRequest;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {

    }
    public function changePassword(Request $request)
    {
        return $this->userService->changePassword($request);
    }
    public function resetPassword(Request $request)
    {
        return $this->userService->resetPassword($request);
    }
    // public function resetPassword(Request $request)
    // {
    //     $rules = [
    //         'new_password' => 'required',
    //         'confirm_password' => 'required',
    //         // Add other validation rules for other fields here...
    //     ];

    //     $validatedData = $request->validate($rules);
    //     if($validatedData){
    //         return $validatedData;
    //     }else{
    //         return "Something Wrong";
    //     }
    // }
}
