<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleDetailRequest;
use App\Http\Requests\UpdateSaleDetailRequest;
use App\Http\Services\SaleDetailService;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function __construct(private SaleDetailService $saleDetailService)
    {
    }
    public function index(Request $request)
    {
        return $this->saleDetailService->index($request);
    }
    public function store(StoreSaleDetailRequest $request)
    {
        return $this->saleDetailService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->saleDetailService->edit($request);
    }
    public function update(UpdateSaleDetailRequest $request)
    {
        return $this->saleDetailService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->saleDetailService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->saleDetailService->filterSaleDetail($request);
    }
}
