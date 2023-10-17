<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentTypeRequest;
use App\Http\Requests\UpdatePaymentTypeRequest;
use App\Http\Services\PaymentTypeService;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function __construct(private PaymentTypeService $paymentTypeService)
    {
    }
    public function index(Request $request)
    {
        return $this->paymentTypeService->index($request);
    }
    public function store(StorePaymentTypeRequest $request)
    {
        return $this->paymentTypeService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->paymentTypeService->edit($request);
    }
    public function update(UpdatePaymentTypeRequest $request)
    {
        return $this->paymentTypeService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->paymentTypeService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->paymentTypeService->filterPaymentType($request);
    }
}
