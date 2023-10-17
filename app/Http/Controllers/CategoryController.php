<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }
    public function index(Request $request)
    {
        return $this->categoryService->index($request);
    }
    public function store(StoreCategoryRequest $request)
    {
        return $this->categoryService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->categoryService->edit($request);
    }
    public function update(UpdateCategoryRequest $request)
    {
        return $this->categoryService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->categoryService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->categoryService->filterCategory($request);
    }
}
