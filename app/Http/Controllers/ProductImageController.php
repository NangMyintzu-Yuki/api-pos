<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Http\Services\ProductImageService;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function __construct(private ProductImageService $productImageService)
    {

    }

    public function store(StoreProductImageRequest $request)
    {
        return $this->productImageService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->productImageService->edit($request);
    }
    public function update(UpdateProductImageRequest $request)
    {
        return $this->productImageService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->productImageService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->productImageService->filterProductImage($request);
    }
}

