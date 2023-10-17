<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Http\Services\DivisionService;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function __construct(private DivisionService $divisionService)
    {

    }
    public function index(Request $request)
    {
        return $this->divisionService->index($request);
    }
    public function store(StoreDivisionRequest $request)
    {
        return $this->divisionService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->divisionService->edit($request);
    }
    public function update(UpdateDivisionRequest $request)
    {
        return $this->divisionService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->divisionService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->divisionService->filter($request);
    }
}
