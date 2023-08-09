<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {

    }
    public function index(Request $request)
    {
        return $this->productService->index($request);
    }
    public function store(StoreProductRequest $request)
    {
        return $this->productService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->productService->edit($request);
    }
    public function update(UpdateProductRequest $request)
    {
        return $this->productService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->productService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->productService->filterProduct($request);
    }

}
