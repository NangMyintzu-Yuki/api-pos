<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseController
{
    public function __construct(
        private User $user,
        private Sale $sale,
        private SaleDetail $saleDetail,
        )
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////


    public function index($request)
    {
        $data = $this->getAll('users', $request['row_count'], 'name', 'asc');
        return $this->sendResponse('User Index Success', $data);
    }


    ////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {

        $request['password'] = Hash::make($request['password']);
        $request['created_at'] = Carbon::now();
        $id = DB::table('users')->insertGetId($request);
        if (isset($request['profile'])) {
            $image = $request['profile'];
            $destinationpath = 'images/users/' . $id;
            $userImage = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->move($destinationpath, $userImage);
            $request['profile'] = "$userImage";
            $request['id'] = $id;
            $this->updateData($request, 'users');
        }
        // $this->insertData($request,'users');
        return $this->sendResponse('User Create Success');
    }


    ///////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->user->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("User Not Found");
        }
        $data = new UserResource($data);
        return $this->sendResponse('User Edit Success', $data);
    }


    ///////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        try {
            $this->beginTransaction();
            if (isset($request['profile'])) {
                if (is_object($request['profile'])) {
                    $image = $request['profile'];
                    $destinationpath = 'images/users/' . $request['id'];
                    $userProfile = date('YmdHis') . '.png';
                    $image->move($destinationpath, $userProfile);
                    $request['profile'] = "$userProfile";
                }else{
                    if (str_contains($request['profile'], 'C:\xampp\tmp')) {
                        if ($request['profile'] != '') {
                            $path = 'images/users/' . $request['id'];
                            File::deleteDirectory(public_path($path));
                            // if (File::exists($path)) {
                            //     File::delete($path);
                            // }
                        }
                        $image = $request['profile'];
                        $destinationpath = 'images/users/' . $request['id'];
                        $userProfile = date('YmdHis') . '.png';
                        $image->move($destinationpath, $userProfile);
                        $request['profile'] = "$userProfile";
                    }
                }
            }
            $this->updateData($request, 'users');
            $user = $this->user->where('id', $request['id'])->whereNull('deleted_at')->first();
            $user = new UserResource($user);
            $this->commit();
            return $this->sendResponse('User Update Success',$user);
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }


    ///////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        try {
            $this->beginTransaction();
            $id = $this->user->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            // $prevImage = $this->user->where('id', $request['id'])->whereNull('deleted_at')->first();

            $multiImagePath = "images/users/" . $request['id'];
            File::deleteDirectory(public_path($multiImagePath));


            $this->deleteById($request['id'], 'users');
            return $this->sendResponse('User Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }


    ///////////////////////////////////////////////////////////////////////////


    public function filterUser($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        if (!$rowCount) {
            $data = User::where('name', 'like', "%$keyword%")
                ->orwhere('username', 'like', "%$keyword%")
                ->orwhere('phone_no', 'like', "%$keyword%")
                ->orwhere('email', 'like', "%$keyword%")
                ->orderBy('name', 'asc')
                ->paginate();
        } else {

            $data = User::where('name', 'like', "%$keyword%")
                ->orwhere('username', 'like', "%$keyword%")
                ->orwhere('phone_no', 'like', "%$keyword%")
                ->orwhere('email', 'like', "%$keyword%")
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        }
        return $this->sendResponse('User Search Success', $data);
    }


    /////////////////////////////////////////////////////////////////////////////////


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
        $userInfo = $this->user->where('id', $userId)->first();
        if (!$userInfo || !Hash::check($request['old_password'], $userInfo->password)) {
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
        if (!isset($request['user_id'])) {
            return $this->sendError("User is Required!");
        }
        if (!isset($request['new_password']) || !isset($request['confirm_password'])) {
            return $this->sendError("Passwords are Required!");
        }
        if ($request['new_password'] != $request['confirm_password']) {
            return $this->sendError("Password does not match!");
        }
        $userId = $request['user_id'];
        $userInfo = $this->user->where('id', $userId)->first();
        if (!$userInfo) {
            return $this->sendError('Something Wrong');
        }
        $userInfo->update([
            'password' => Hash::make($request['new_password']),
            'updated_by' => $userId,
            'updated_at' => Carbon::now()
        ]);
        return $this->sendResponse('User Reset Password Success');
    }


    ///////////////////////////////////////////////////////////////

    public function changeStatus(array $request)
    {
        $data = $this->user->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("User Not Found");
        }
        // $request['updated_by'] = auth()->user()->id;
        $request['updated_at'] = Carbon::now();
        $this->updateData($request, 'users');
        return $this->sendResponse("User Status Update Success");
    }

    ///////////////////////////////////////////////////////////////////////////

    public function getOrderedItems($request)
    {
        $data = $this->sale->where('user_id', $request['user_id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Data Not Found");
        }
        return $this->sendResponse('Data Fetch Success', $data);
    }

}
