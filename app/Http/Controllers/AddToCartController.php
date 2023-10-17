<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddToCartRequest;
use App\Http\Requests\UpdateAddToCartRequest;
use App\Http\Services\AddToCartService;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    public function __construct(private AddToCartService $addToCartService)
    {
    }
    public function index(Request $request)
    {
        return $this->addToCartService->index($request);
    }
    public function store(StoreAddToCartRequest $request)
    {
        return $this->addToCartService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->addToCartService->edit($request);
    }
    public function update(UpdateAddToCartRequest $request)
    {
        return $this->addToCartService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->addToCartService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->addToCartService->filterAddToCart($request);
    }
}
