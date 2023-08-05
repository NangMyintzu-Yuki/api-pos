<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OperatorService extends Controller{
    public function __construct(private Operator $operator)
    {

    }
    public function index()
    {

    }
    public function edit($request)
    {
        $data = $this->operator->where('id',$request['id'])->first();
        $data = new OperatorResource($data);
        return $this->sendResponse('Operator Edit Success',$data);
    }

    public function changePassword($request)
    {
        // dd($request);
        if(!isset($request['operator_id'])){
            return $this->sendError(("Operator is Reqeuired!"));
        }
        if(!isset($request['new_password'])){
            return $this->sendError(("New Password is Reqeuired!"));
        }
        if(!isset($request['old_password'])){
            return $this->sendError(("Old Password is Reqeuired!"));
        }
        $operatorId = $request['operator_id'];
        $operatorInfo = $this->operator->where('id',$operatorId)->first();
        if(!$operatorInfo || !Hash::check($request['old_password'],$operatorInfo->password)){
            return $this->sendError(("Password Incorrect!"));
        }
        $operatorInfo->update([
            'password' => Hash::make($request['new_password']),
            // 'updated_by' => auth()->user()->name,
            'updated_at' => Carbon::now(),
        ]);
        return $this->sendResponse('Operator Password Change Success');
    }

}
