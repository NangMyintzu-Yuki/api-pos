<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService extends Controller
{
    public function __construct(private User $user)
    {
    }
    public function index()
    {
    }
    public function edit($request)
    {
        $data = $this->user->where('id', $request['id'])->first();
        $data = new UserResource($data);
        return $this->sendResponse('User Edit Success', $data);
    }

    public function changePassword($request)
    {
        // dd($request);
        if (!isset($request['user_id'])) {
            return $this->sendError(("User is Reqeuired!"));
        }
        if (!isset($request['new_password'])) {
            return $this->sendError(("New Password is Reqeuired!"));
        }
        if (!isset($request['old_password'])) {
            return $this->sendError(("Old Password is Reqeuired!"));
        }
        $userId = $request['user_id'];
        $userInfo = $this->user->where('id',$userId)->first();
        if(!$userInfo || !Hash::check($request['old_password'],$userInfo->password)){
            return $this->sendError("Password Incorrect!");
        }
        $userInfo->update([
            'password' => Hash::make($request['new_password']),
            // 'updated_by' => auth()->user()->name,
            'updated_at' => Carbon::now(),
        ]);
        return $this->sendResponse('User Password Change Success');
    }
    public function resetPassword($request)
    {
        if(!isset($request['user_id'])){
            return $this->sendError("User is Required!");
        }
        if(!isset($request['new_password']) || !isset($request['confirm_password'])){
            return $this->sendError("Passwords are Required!");
        }
        if($request['new_password'] != $request['confirm_password']){
            return $this->sendError("Password does not match!");
        }
        $userId = $request['user_id'];
        $userInfo = $this->user->where('id',$userId)->first();
        if(!$userInfo){
            return $this->sendError('Something Wrong');
        }
        $userInfo->update([
            'password' => Hash::make($request['new_password']),
            'updated_by' => $userId,
            'updated_at' => Carbon::now()
        ]);
        return $this->sendResponse('User Reset Password Success');
    }
}
