<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleChangeStatusRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(private SaleService $saleService)
    {
    }
    public function index(Request $request)
    {
        return $this->saleService->index($request);
    }
    public function store(StoreSaleRequest $request)
    {
        return $this->saleService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->saleService->edit($request);
    }
    public function update(UpdateSaleRequest $request)
    {
        return $this->saleService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->saleService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->saleService->filterSale($request);
    }
    public function change_status(SaleChangeStatusRequest $request)
    {
        return $this->saleService->change_status($request->validated());
    }
    public function saleWithUserId(Request $request)
    {
        return $this->saleService->saleWithUserId($request);
    }
}
