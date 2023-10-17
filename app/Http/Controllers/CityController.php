<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(private CityService $cityService)
    {
    }
    public function index(Request $request)
    {
        return $this->cityService->index($request);
    }
    public function store(StoreCityRequest $request)
    {
        return $this->cityService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->cityService->edit($request);
    }
    public function update(UpdateCityRequest $request)
    {
        return $this->cityService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->cityService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->cityService->filter($request);
    }
}
