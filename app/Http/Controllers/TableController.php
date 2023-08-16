<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Http\Services\TableService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct(private TableService $tableService)
    {
    }
    public function index(Request $request)
    {
        return $this->tableService->index($request);
    }
    public function store(StoreTableRequest $request)
    {
        return $this->tableService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->tableService->edit($request);
    }
    public function update(UpdateTableRequest $request)
    {
        return $this->tableService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->tableService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->tableService->filterTable($request);
    }
}
