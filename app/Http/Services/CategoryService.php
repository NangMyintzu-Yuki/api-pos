<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Category;

class CategoryService extends BaseController{
    public function __construct(private Category $category)
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Category::with("branch")->paginate($request['row_count']);
        return $this->sendResponse('Caregory Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'categories');
        return $this->sendResponse('Category Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->category->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Category Not Found');
        }
        return $this->sendResponse('Category Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'categories');
        return $this->sendResponse('Category Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'categories');
        return $this->sendResponse('Category Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterCategory($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Category::where('name', 'like', "%$keyword%")->paginate($rowCount);
        return $this->sendResponse('Category Search Success', $data);
    }
}
