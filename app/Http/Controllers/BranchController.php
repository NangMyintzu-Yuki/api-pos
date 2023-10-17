<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(private BranchService $branchService)
    {

    }
    public function index(Request $request)
    {
        return $this->branchService->index($request);
    }
    public function store(StoreBranchRequest $request)
    {
        return $this->branchService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->branchService->edit($request);
    }
    public function update(UpdateBranchRequest $request)
    {
        return $this->branchService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->branchService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->branchService->filterBranch($request);
    }
}
