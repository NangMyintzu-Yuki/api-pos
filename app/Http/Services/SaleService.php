<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Dashboard;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService extends BaseController
{
    public function __construct(
        private Sale $sale,
        private Payment $payment,
        private Dashboard $dashboard,
        private SaleDetail $saleDetail,
    ) {
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
            ->orderBy('voucher_no', 'desc')
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
            if (isset($request['user_id'])) {
                $sale['user_id'] = $request['user_id'];
            }
            // $sale['user_qty'] = $request['user_qty'];
            Log::info("sale", $sale);
            $id = $this->insertGetId($sale, 'sales');

            if (isset($request['user_id'])) {
                $hasUser = $this->dashboard->where('branch_id', $request['branch_id'])->where('user_id', $request['user_id'])->whereNull('deleted_at')->first();
                if ($hasUser) {
                    $newData['count'] = $hasUser->count + 1;
                    $newData['amount'] = $hasUser->total_amount + $request['total_amount'];
                    $newData['updated_at'] = Carbon::now();
                    $this->dashboard->where('id', $hasUser->id)->update($newData);
                } else {
                    $newData['branch_id'] = $request['branch_id'];
                    $newData['user_id'] = $request['user_id'];
                    $newData['amount'] =  $request['total_amount'];
                    $newData['count'] = 1;
                    $this->insertData($newData, 'dashboards');
                }
            }

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

                        $hasProduct = $this->dashboard->where('product_id', $detail['id'])->whereNull('deleted_at')->first();
                        if ($hasProduct) {
                            $newData['count'] = $hasProduct['count'] + $detail['quantity'];
                            $newData['updated_at'] = Carbon::now();
                            $this->dashboard->where('id', $hasProduct->id)->update($newData);
                        } else {
                            $newData['branch_id'] = $request['branch_id'];
                            $newData['product_id'] = $detail['id'];
                            $newData['count'] = $detail['quantity'];
                            $this->insertData($newData, 'dashboards');
                        }
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


        try {
            $this->beginTransaction();

            $saleData = $this->sale->where('id', $request['id'])->whereNull('deleted_at')->first();
            $hasUser = $this->dashboard->where('product_id', $saleData['user_id'])->whereNull('deleted_at')->first();
            if ($hasUser) {
                $newData['count'] = $hasUser['count'] - 1;
                $newData['updated_at'] = Carbon::now();
                $newData['amount'] = $hasUser->total_amount - $saleData['total_amount'];
                $this->dashboard->where('id', $hasUser->id)->update($newData);
            }

            $sale['id'] = $request['id'];
            $sale['date'] = $request['date'];
            $sale['branch_id'] = $request['branch_id'];
            $sale['voucher_no'] = $request['voucher_no'];
            $sale['table_id'] = $request['table_id'];
            $sale['total_amount'] = $request['total_amount'];
            if (isset($request['user_id'])) {
                $sale['user_id'] = $request['user_id'];
            }

            $this->updateData($sale, 'sales');
            $newData['count'] = $hasUser['count'] - 1;
            $newData['updated_at'] = Carbon::now();
            $newData['amount'] = $hasUser->total_amount + $request['total_amount'];
            $this->dashboard->where('id', $hasUser->id)->update($newData);



            $saleDetails = $this->saleDetail->where('sale_id', $request['id'])->whereNull('deleted_at')->get();
            if (count($saleDetails) > 0) {
                foreach ($saleDetails as $detail) {
                    $hasProduct = $this->dashboard->where('product_id', $detail['product_id'])->whereNull('deleted_at')->first();
                    if ($hasProduct) {
                        $newData['count'] = $hasProduct['count'] - $detail['quantity'];
                        $newData['updated_at'] = Carbon::now();
                        $this->dashboard->where('id', $hasProduct->id)->update($newData);
                    }
                }
            }
            DB::table('sale_details')->where('sale_id', $request['id'])->delete();


            // info("id=>", $id);
            if (isset($request['orderDetail'])) {
                if (count($request['orderDetail']) > 0) {
                    foreach ($request['orderDetail'] as $detail) {
                        info($request['orderDetail']);
                        if ($detail != false) {
                            $saleDetail['sale_id'] = $request['id'];
                            $saleDetail['product_id'] = $detail['id'];
                            $saleDetail['price'] = $detail['price'];
                            $saleDetail['quantity'] = $detail['quantity'];
                            $saleDetail['amount'] = $detail['price'] * $detail['quantity'];
                            $saleDetail['status'] = $request['status'];
                            $this->insertData($saleDetail, 'sale_details');

                            $hasProduct = $this->dashboard->where('product_id', $detail['id'])->whereNull('deleted_at')->first();
                            if ($hasProduct) {
                                $newData['count'] = $hasProduct['count'] + $detail['quantity'];
                                $newData['updated_at'] = Carbon::now();
                                $this->dashboard->where('id', $hasProduct->id)->update($newData);
                            } else {
                                $newData['branch_id'] = $request['branch_id'];
                                $newData['product_id'] = $detail['id'];
                                $newData['count'] = $detail['quantity'];
                                $this->insertData($newData, 'dashboards');
                            }
                        }
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
            $sale = $this->sale->where('id', $request['id'])->whereNull('deleted_at')->first();
            if ($sale) {
                $hasUser = $this->dashboard->where('product_id', $sale['user_id'])->whereNull('deleted_at')->first();
                if ($hasUser) {
                    $newData['count'] = $hasUser['count'] - 1;
                    $newData['updated_at'] = Carbon::now();
                    $newData['amount'] = $hasUser->total_amount + $request['total_amount'];
                    $this->dashboard->where('id', $hasUser->id)->update($newData);
                }
            }

            $saleDetails = $this->saleDetail->where('sale_id', $request['id'])->whereNull('deleted_at')->get();
            if (count($saleDetails) > 0) {
                foreach ($saleDetails as $detail) {
                    $hasProduct = $this->dashboard->where('product_id', $detail['product_id'])->whereNull('deleted_at')->first();
                    if ($hasProduct) {
                        $newData['count'] = $hasProduct['count'] - $detail['quantity'];
                        $newData['updated_at'] = Carbon::now();
                        $this->dashboard->where('id', $hasProduct->id)->update($newData);
                    }
                }
            }
            $this->deletedByAttr('sale_id', $request['id'], 'sale_details');

            $payment = $this->payment->where('sale_id', $request['id'])->first();
            if ($payment) {
                return $this->sendError("This Payment is already used. Can't delete!!");
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
        if ($status && !$branchId) {
            $data = $this->sale->where('status', $status)->with([
                'branch' => function ($q) {
                    $q->select("id", 'name');
                },

                'user' => function ($q) {
                    $q->select('id', 'name', 'username', 'address', 'phone_no');
                },
                'table' => function ($q) {
                    $q->select("id", "table_no");
                },
                'sale_detail' => with([
                    'product' => function ($q) {
                        $q->select('id', 'name');
                    },
                ]),


            ])->orderBy('voucher_no', 'desc')->paginate($rowCount);
        } else if ($request['branch_id']) {
            $data = $this->sale->where('voucher_no', 'like', "%$keyword%")
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
                    },
                    'sale_detail' => with(['product' => function ($q) {
                        $q->select('id', 'name');
                    }])
                ])
                ->orderBy('voucher_no', 'desc')
                ->paginate($rowCount);
        } else {
            $data = $this->sale->where('voucher_no', 'like', "%$keyword%")
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
                    'sale_detail' => with([
                        'product' => function ($q) {
                            $q->select('id', 'name');
                        },
                    ]),
                ])
                ->orderBy('voucher_no', 'desc')
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
