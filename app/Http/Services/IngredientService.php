<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Ingredient;
use Exception;
use Illuminate\Support\Facades\File;

class IngredientService extends BaseController
{
    public function __construct(private Ingredient $ingredient)
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('ingredients', $request['row_count']);

        return $this->sendResponse('Ingredient Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'ingredients');
        return $this->sendResponse('Ingredient Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->ingredient->where('id', $request['id'])
            ->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Ingredient Not Found');
        }
        return $this->sendResponse('Ingredient Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        // dd($request);
        try {
            $this->updateData($request, 'ingredients');
            return $this->sendResponse('Ingredient Update Success');
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try {
            $id = $this->ingredient->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $this->deleteById($request['id'], 'ingredients');
            return $this->sendResponse('Ingredient Delete Success');
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filter($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Ingredient::where('name', 'like', "%$keyword%")
            ->paginate($rowCount);
        return $this->sendResponse('Ingredient Search Success', $data);
    }
}
