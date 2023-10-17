<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTownshipRequest;
use App\Http\Requests\UpdateTownshipRequest;
use App\Http\Services\TownshipService;
use Illuminate\Http\Request;

class TownshipController extends Controller
{
    public function __construct(private TownshipService $townshipService)
    {
    }
    public function index(Request $request)
    {
        return $this->townshipService->index($request);
    }
    public function store(StoreTownshipRequest $request)
    {
        return $this->townshipService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->townshipService->edit($request);
    }
    public function update(UpdateTownshipRequest $request)
    {
        return $this->townshipService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->townshipService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->townshipService->filter($request);
    }
}
