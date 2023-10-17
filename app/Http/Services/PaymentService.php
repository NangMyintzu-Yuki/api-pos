<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentService extends BaseController
{
    public function __construct(private Payment $payment)
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Payment::with([
            'branch' => function ($q) {
                $q->select('id', 'name');
            },
            'payment_type' => function ($q) {
                $q->select('id', 'name');
            },
            'sale' => function ($q) {
                $q->select('id', 'voucher_no', 'date', 'total_amount');
            },
            'cash_collector' => function ($q) {
                $q->select('id', 'name', 'username');
            }
        ])
            ->paginate($request['row_count']);
        return $this->sendResponse('Payment Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'payments');
        return $this->sendResponse('Payment Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this
            ->payment
            ->where('id', $request['id'])
            ->whereNull('deleted_at')
            ->with([
                'branch' => function ($q) {
                    $q->select('id', 'name');
                },
                'payment_type' => function ($q) {
                    $q->select('id', 'name');
                },
                'sale' => function ($q) {
                    $q->select('id', 'voucher_no', 'date', 'total_amount');
                },
                'cash_collector' => function ($q) {
                    $q->select('id', 'name', 'username');
                }
            ])
            ->first();
        if (!$data) {
            return $this->sendResponse('Payment Not Found');
        }
        return $this->sendResponse('Payment Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'payments');
        return $this->sendResponse('Sale Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $id = $this->payment->find($request['id']);
        if (!$id) {
            return $this->sendError("No Record to Delete");
        }
        $this->deleteById($request['id'], 'payments');
        return $this->sendResponse('Payment Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterPayment($request)
    {
        $rowCount = $request['row_count'];
        $branchId =  $request['branch_id'];
        $saleId =  $request['sale_id'];
        $operatorId =  $request['operator_id'];
        $paymentTypeId =  $request['payment_type_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        if ($request['branch_id'] && !$saleId && !$operatorId && !$paymentTypeId) {
            $data = Payment::where('branch_id', $branchId)
                ->with([
                    'branch' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'payment_type' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'sale' => function ($q) {
                        $q->select('id', 'voucher_no', 'date', 'total_amount');
                    },
                    'cash_collector' => function ($q) {
                        $q->select('id', 'name', 'username');
                    }
                ])->paginate($rowCount);
        } else if ($request['branch_id']) {
            $data = Payment::where('sale_id', $saleId)
                ->where('payment_type_id', $paymentTypeId)
                ->where('cash_collected_by', $operatorId)
                ->where('branch_id', $branchId)
                ->with([
                    'branch' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'payment_type' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'sale' => function ($q) {
                        $q->select('id', 'voucher_no', 'date', 'total_amount');
                    },
                    'cash_collector' => function ($q) {
                        $q->select('id', 'name', 'username');
                    }
                ])
                ->paginate($rowCount);
        } else {
            $data = Payment::where('sale_id', $saleId)
                ->where('payment_type_id', $paymentTypeId)
                ->where('cash_collected_by', $operatorId)
                ->with([
                    'branch' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'payment_type' => function ($q) {
                        $q->select('id', 'name');
                    },
                    'sale' => function ($q) {
                        $q->select('id', 'voucher_no', 'date', 'total_amount');
                    },
                    'cash_collector' => function ($q) {
                        $q->select('id', 'name', 'username');
                    }
                ])
                ->paginate($rowCount);
        }
        return $this->sendResponse('Payment Search Success', $data);
    }

    ////////////////////////////////////////////////////////////////////////////////////////

    public function change_status($request)
    {
        $data = $this->payment->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Something Wrong");
        }
        // $request['updated_by'] = auth()->user()->id;
        $request['updated_at'] = Carbon::now();
        $this->updateData($request, 'payments');
        return $this->sendResponse("Payment Status Update Success");
    }

    ///////////////////////////////////////////////////////////////////////////////////////////


    public function make_invoice($request)
    {
        return "make_invoice";
    }
}
