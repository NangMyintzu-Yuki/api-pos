<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentChangeStatusRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }
    public function index(Request $request)
    {
        return $this->paymentService->index($request);
    }
    public function store(StorePaymentRequest $request)
    {
        return $this->paymentService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->paymentService->edit($request);
    }
    public function update(UpdatePaymentRequest $request)
    {
        return $this->paymentService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->paymentService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->paymentService->filterPayment($request);
    }
    public function change_status(PaymentChangeStatusRequest $request)
    {
        return $this->paymentService->change_status($request->validated());
    }
    public function make_invoice(Request $request)
    {
        return $this->paymentService->make_invoice($request);
    }
}
