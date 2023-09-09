<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService extends BaseController
{
    public function __construct(
        private Sale $sale,
        private Payment $payment,
        )
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Sale::with([
            'branch' => function ($q) {
                $q->select("id", 'name');
            },
            'user' => function ($q) {
                $q->select('id', 'name', 'username', 'address', 'phone_no');
            },
            'table' => function ($q) {
                $q->select("id", "table_no");
            },
            'sale_detail' => with(['product' => function ($q) {
                $q->select('id', 'name');
            }])
        ])
            ->paginate($request['row_count']);
        return $this->sendResponse('Sale Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        info($request);
        Log::info($request);

        try {
            $this->beginTransaction();


            $sale['date'] = $request['date'];
            $sale['branch_id'] = $request['branch_id'];
            $sale['voucher_no'] = $request['voucher_no'];
            $sale['table_id'] = $request['table_id'];
            $sale['total_amount'] = $request['total_amount'];
            // $sale['user_id'] = $request['user_id'];
            // $sale['user_qty'] = $request['user_qty'];
            Log::info("sale", $sale);
            $id = $this->insertGetId($sale, 'sales');

            // info("id=>", $id);
            if (isset($request['orderDetail'])) {
                if (count($request['orderDetail']) > 0) {
                    foreach ($request['orderDetail'] as $detail) {
                        $saleDetail['sale_id'] = $id;
                        $saleDetail['product_id'] = $detail['id'];
                        $saleDetail['price'] = $detail['price'];
                        $saleDetail['quantity'] = $detail['quantity'];
                        $saleDetail['amount'] = $detail['price'] * $detail['quantity'];
                        $saleDetail['status'] = $request['status'];
                        Log::info($saleDetail);
                        $this->insertData($saleDetail, 'sale_details');
                    }
                }
            }
            // $this->insertData($request, 'sales');
            $this->commit();
            return $this->sendResponse('Sale Create Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this
            ->sale
            ->where('id', $request['id'])
            ->whereNull('deleted_at')
            ->with([
                'branch' => function ($q) {
                    $q->select("id", 'name');
                },
                'user' => function ($q) {
                    $q->select('id', 'name', 'username', 'address', 'phone_no');
                },
                'table' => function ($q) {
                    $q->select("id", "table_no");
                },
                'sale_detail' => with(
                    [
                        // 'quantity',
                        // 'product' => function ($q) {
                        // $q->select('id', 'name','price')->with('product_image');
                        // }
                        'product' => with(['product_image']),
                    ]
                )
            ])
            ->first();
        if (!$data) {
            return $this->sendResponse('Sale Not Found');
        }
        return $this->sendResponse('Sale Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        info($request);
        Log::info($request);

        try {
            $this->beginTransaction();


            $sale['id'] = $request['id'];
            $sale['date'] = $request['date'];
            $sale['branch_id'] = $request['branch_id'];
            $sale['voucher_no'] = $request['voucher_no'];
            $sale['table_id'] = $request['table_id'];
            $sale['total_amount'] = $request['total_amount'];

            $this->updateData($sale, 'sales');
            DB::table('sale_details')->where('sale_id', $request['id'])->delete();


            // info("id=>", $id);
            if (isset($request['orderDetail'])) {
                if (count($request['orderDetail']) > 0) {
                    foreach ($request['orderDetail'] as $detail) {
                        $saleDetail['sale_id'] = $request['id'];
                        $saleDetail['product_id'] = $detail['id'];
                        $saleDetail['price'] = $detail['price'];
                        $saleDetail['quantity'] = $detail['quantity'];
                        $saleDetail['amount'] = $detail['price'] * $detail['quantity'];
                        $saleDetail['status'] = $request['status'];
                        Log::info($saleDetail);
                        $this->insertData($saleDetail, 'sale_details');
                    }
                }
            }
            // $this->insertData($request, 'sales');

            $this->commit();
            return $this->sendResponse('Sale Update Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {

        try {
            $this->beginTransaction();

            $id = $this->sale->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }

            $this->deletedByAttr('sale_id',$request['id'],'sale_details');

            $payment = $this->payment->where('sale_id',$request['id'])->first();
            if($payment){
                return $this->sendError("This Payment has already used. Can't delete!!");
            }
            $this->deleteById($request['id'], 'sales');
            $this->commit();
            return $this->sendResponse('Sale Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }

    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterSale($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $branchId =  $request['branch_id'];
        $userId =  $request['user_id'];
        $tableId =  $request['table_id'];
        $status =  $request['status'];
        $rowCount = !$rowCount ? null : $rowCount;
        if($status){
            $data = Sale::where('status', $status)->get();
        }
        if ($request['branch_id']) {
            $data = Sale::where('voucher_no', 'like', "%$keyword%")
                ->where('user_id', $userId)
                ->where('table_id', $tableId)
                ->where('branch_id', $branchId)
                ->with([
                    'branch' => function ($q) {
                        $q->select("id", 'name');
                    },
                    'user' => function ($q) {
                        $q->select('id', 'name', 'username', 'address', 'phone_no');
                    },
                    'table' => function ($q) {
                        $q->select("id", "table_no");
                    }
                ])
                ->paginate($rowCount);
        } else {
            $data = Sale::where('voucher_no', 'like', "%$keyword%")
                ->with([
                    'branch' => function ($q) {
                        $q->select("id", 'name');
                    },
                    'user' => function ($q) {
                        $q->select('id', 'name', 'username', 'address', 'phone_no');
                    },
                    'table' => function ($q) {
                        $q->select("id", "table_no");
                    }
                ])
                ->paginate($rowCount);
        }
        return $this->sendResponse('Sale Search Success', $data);
    }

    ////////////////////////////////////////////////////////////////////////////////////////

    public function change_status($request)
    {
        $data = $this->sale->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Something Wrong");
        }
        // $request['updated_by'] = auth()->user()->id;
        $request['updated_at'] = Carbon::now();
        $this->updateData($request, 'sales');
        return $this->sendResponse("Sale Status Update Success");
    }
}
