<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKitchenRequest;
use App\Http\Requests\UpdateKitchenRequest;
use App\Http\Services\KitchenService;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function __construct(private KitchenService $kitchenService)
    {

    }
    public function index(Request $request)
    {
        return $this->kitchenService->index($request);
    }
    public function store(StoreKitchenRequest $request)
    {
        return $this->kitchenService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->kitchenService->edit($request);
    }
    public function update(UpdateKitchenRequest $request)
    {
        return $this->kitchenService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->kitchenService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->kitchenService->filterKitchen($request);
    }
}
