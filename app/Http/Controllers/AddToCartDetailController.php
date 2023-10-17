<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddToCartDetailRequest;
use App\Http\Requests\UpdateAddToCartDetailRequest;
use App\Http\Services\AddToCartDetailService;
use Illuminate\Http\Request;

class AddToCartDetailController extends Controller
{
    public function __construct(private AddToCartDetailService $addToCartDetailService)
    {
    }
    public function index(Request $request)
    {
        return $this->addToCartDetailService->index($request);
    }
    public function store(StoreAddToCartDetailRequest $request)
    {
        return $this->addToCartDetailService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->addToCartDetailService->edit($request);
    }
    public function update(UpdateAddToCartDetailRequest $request)
    {
        return $this->addToCartDetailService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->addToCartDetailService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->addToCartDetailService->filterAddToCartDetail($request);
    }
}
