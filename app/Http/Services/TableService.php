<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Sale;
use App\Models\Table;
use Carbon\Carbon;

class TableService extends BaseController
{
    public function __construct(
        private Table $table,
        private Sale $sale,
        )
    {

    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Table::with([
                    'branch' => function($query){
                        $query->select('id','name');
                    }])
                    ->orderBy('table_no','asc')
                    ->paginate($request['row_count']);
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
        $data = $this
                ->table
                ->where('id', $request['id'])
                ->whereNull('deleted_at')
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                ->first();
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
        try{
            $this->beginTransaction();
            $id = $this->table->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $sale = $this->sale->where('table_id',$request['id'])->first();
            if($sale){
                return $this->sendError("This Table is already used. Can't delete!!");
            }
            $this->deleteById($request['id'], 'tables');
            $this->commit();
            return $this->sendResponse('Table Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////


    public function change_status($request)
    {
        $data = $this->table->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Table Not Found");
        }
        // $request['updated_by'] = auth()->user()->id;
        $request['updated_at'] = Carbon::now();
        $this->updateData($request, 'tables');
        return $this->sendResponse("Table Status Update Success");
    }






    /////////////////////////////////////////////////////////////////////////////////////


    public function filterTable($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $branchId =  $request['branch_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        if($request['branch_id']){
            $data = Table::where('table_no', 'like', "%$keyword%")
                            ->where('branch_id', $branchId)
                            ->with([
                                'branch' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->orderBy('table_no','asc')
                            ->paginate($rowCount);
        }else{
            $data = Table::where('table_no', 'like', "%$keyword%")
                            ->with([
                                'branch' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->orderBy('table_no','asc')
                            ->paginate($rowCount);
        }
        return $this->sendResponse('Table Search Success', $data);
    }

}
