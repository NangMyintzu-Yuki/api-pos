<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Payment;
use App\Models\PaymentType;

class PaymentTypeService extends BaseController
{
    public function __construct(
        private PaymentType $paymentType,
        private Payment $payment,
    ) {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('payment_types', $request['row_count']);
        return $this->sendResponse('Payment Type Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'payment_types');
        return $this->sendResponse('Payment Type Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->paymentType->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Payment Type Not Found');
        }
        return $this->sendResponse('Payment Type Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'payment_types');
        return $this->sendResponse('Payment Type Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try {
            $this->beginTransaction();
            $id = $this->paymentType->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }

            $payment = $this->payment->where('payment_type_id', $request['id'])->first();
            if ($payment) {
                return $this->sendError("This Payment Type is already used. Can't delete!!");
            }
            $this->deleteById($request['id'], 'payment_types');
            $this->commit();
            return $this->sendResponse('Payment Type Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterPaymentType($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = PaymentType::where('name', 'like', "%$keyword%")->orderBy('name','asc')->paginate($rowCount);
        return $this->sendResponse('Payment Type Search Success', $data);
    }
}
