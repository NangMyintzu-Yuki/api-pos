<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OperatorService extends BaseController{
    public function __construct(private Operator $operator)
    {

    }

    /////////////////////////////////////////////////////////////////////////////////////


    public function index($request)
    {
        // $data = $this->getAll('operators',$request['row_count']);
        // $data = $this->operator->get();
        // $data = OperatorResource::collection($data->items())->response()->getData(true);

        $data= Operator::with(['branch'=>function($q){
                            $q->select('id','name');
                        }])
                        ->paginate($request['row_count']);
        return $this->sendResponse('Operator Index Success',$data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function store(array $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['created_at'] = Carbon::now();
        $this->insertData($request, 'operators');
        return $this->sendResponse('Operator Create Success');
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function edit($request)
    {
        $data = $this
                ->operator
                ->where('id',$request['id'])
                ->with(['branch' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->first();
        if (!$data) {
            return $this->sendResponse("There is no data with");
        }
        $data = new OperatorResource($data);
        return $this->sendResponse('Operator Edit Success',$data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'operators');
        return $this->sendResponse('Operator Update Success');
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        $this->deleteById($request['id'],'operators');
        return $this->sendResponse('Operator Delete Success');
    }



    /////////////////////////////////////////////////////////////////////////////////////


    public function filterOperator($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        if(!$rowCount){
            $data = Operator::with('branch')
            ->where('name', 'like', "%$keyword%")
            ->orwhere('username', 'link', "%$keyword%")
            ->with(['branch' => function ($q) {
                $q->select('id', 'name');
            }])
            ->paginate();
        }else{
            $data = Operator::with('branch')
                ->where('name', 'like', "%$keyword%")
                ->orwhere('username','link',"%$keyword%")
                ->with(['branch' => function ($q) {
                    $q->select('id', 'name');
                }])
                ->paginate($rowCount);
        }
        return $this->sendResponse('Operator Search Success',$data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function changePassword(array $request)
    {
        // dd($request);

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
