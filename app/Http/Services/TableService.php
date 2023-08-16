<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Table;

class TableService extends BaseController
{
    public function __construct(private Table $table)
    {

    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Table::with(['branch'])->paginate($request['row_count']);
        return $this->sendResponse('Table Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'tables');
        return $this->sendResponse('Table Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->table->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Table Not Found');
        }
        return $this->sendResponse('Table Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'tables');
        return $this->sendResponse('Table Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'tables');
        return $this->sendResponse('Table Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterTable($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $branchId =  $request['branch_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        if($request['branch_id']){
            $data = Table::where('table_no', 'like', "%$keyword%")->where('branch_id', $branchId)->paginate($rowCount);
        }else{
            $data = Table::where('table_no', 'like', "%$keyword%")->paginate($rowCount);
        }
        return $this->sendResponse('Table Search Success', $data);
    }

}
