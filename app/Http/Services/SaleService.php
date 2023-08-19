<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Sale;
use Carbon\Carbon;

class SaleService extends BaseController
{
    public function __construct(private Sale $sale)
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Sale::with(['branch','user','table'])->paginate($request['row_count']);
        return $this->sendResponse('Sale Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'sales');
        return $this->sendResponse('Sale Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->sale->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Sale Not Found');
        }
        return $this->sendResponse('Sale Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'sales');
        return $this->sendResponse('Sale Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'sales');
        return $this->sendResponse('Sale Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterSale($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $branchId =  $request['branch_id'];
        $userId =  $request['user_id'];
        $tableId =  $request['table_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        if ($request['branch_id']) {
            $data = Sale::where('voucher_no', 'like', "%$keyword%")->where('user_id',$userId)->where('table_id',$tableId)->where('branch_id', $branchId)->paginate($rowCount);
        }
         else {
            $data = Sale::where('voucher_no', 'like', "%$keyword%")->paginate($rowCount);
        }
        return $this->sendResponse('Sale Search Success', $data);
    }

    ////////////////////////////////////////////////////////////////////////////////////////

    public function change_status($request)
    {
        $data = $this->sale->where('id',$request['id'])->whereNull('deleted_at')->first();
        if(!$data)
        {
            return $this->sendResponse("Something Wrong");
        }
        // $request['updated_by'] = auth()->user()->id;
        $request['updated_at'] = Carbon::now();
        $this->updateData($request, 'sales');
        return $this->sendResponse("Sale Status Update Success");
    }
}
