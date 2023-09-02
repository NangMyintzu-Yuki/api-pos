<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Http\Services\IngredientService;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function __construct(private IngredientService $ingredientService)
    {
    }
    public function index(Request $request)
    {
        return $this->ingredientService->index($request);
    }
    public function store(StoreIngredientRequest $request)
    {
        return $this->ingredientService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->ingredientService->edit($request);
    }
    public function update(UpdateIngredientRequest $request)
    {
        return $this->ingredientService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->ingredientService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->ingredientService->filter($request);
    }
}
