<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Branch;

class BranchService extends BaseController{
    public function __construct(private Branch $branch)
    {

    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('branches',$request['row_count']);
        return $this->sendResponse('Branch Index Success',$data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request,'branches');
        return $this->sendResponse('Branch Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->branch->where('id',$request['id'])->whereNull('deleted_at')->first();
        if(!$data)
        {
            return $this->sendResponse('Branch Not Found');
        }
        return $this->sendResponse('Branch Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request,'branches');
        return $this->sendResponse('Branch Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'],'branches');
        return $this->sendResponse('Branch Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterBranch($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Branch::where('name','like',"%$keyword%")->paginate($rowCount);
        return $this->sendResponse('Branch Search Success',$data);
    }
}
