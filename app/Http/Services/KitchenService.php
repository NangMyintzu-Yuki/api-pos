<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Kitchen;

class KitchenService extends BaseController
{
    public function __construct(private Kitchen $kitchen)
    {

    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Kitchen::with([
                'branch' => function($query){
                    $query->select('id','name');
                },
                'operator' => function ($query){
                    $query->select('id','name','username');
                }
                ])
            ->paginate($request['row_count']);
        return $this->sendResponse('Kitchen Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request,'kitchens');
        return $this->sendResponse('Kitchen Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this
                ->kitchen
                ->where('id',$request['id'])
                ->whereNull('deleted_at')
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'operator' => function ($query) {
                        $query->select('id', 'name', 'username');
                    }
                ])
                ->first();
        if(!$data)
        {
            return $this->sendResponse('Kitchen Not Found');
        }
        return $this->sendResponse('Kitchen Edit Success',$data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request,'kitchens');
        return $this->sendResponse('Kitchen Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $id = $this->kitchen->find($request['id']);
        if (!$id) {
            return $this->sendError("No Record to Delete");
        }
        $this->deleteById($request['id'],'kitchens');
        return $this->sendResponse('Kitchen Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterKitchen($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $operatorId = $request['operator_id'];
        $branchId = $request['branch_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Kitchen::where('name','like',"%$keyword%")
                        ->orWhere('branch_id',$branchId)
                        ->orWhere('operator_id',$operatorId)
                        ->with([
                            'branch' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'operator' => function ($query) {
                                $query->select('id', 'name', 'username');
                            }
                        ])
                        ->paginate($rowCount);
        return $this->sendResponse('Kitchen Search Success',$data);
    }
}
