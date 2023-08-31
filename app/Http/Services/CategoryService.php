<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\File;

class CategoryService extends BaseController{
    public function __construct(private Category $category)
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Category::with(["branch" => function($q){
            $q->select('id','name',
        );
      },
        "parent" => function($query){
            $query->select('id','name');
        }
        ])->paginate($request['row_count']);
        return $this->sendResponse('Caregory Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        if($request['parent_id'] == 'null'){
            $request['parent_id'] =null;
        }
        if(isset($request['image'])){
            $image = $request['image'];
            $destinationpath = 'images/categories';
            $categoryImage = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->move($destinationpath,$categoryImage);
            $request['image'] = "$categoryImage";
        }
        $this->insertData($request, 'categories');
        return $this->sendResponse('Category Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->category->where('id', $request['id'])
            ->with(["branch" => function($q){
                $q->select('id','name');
            },
            "parent" => function ($query) {
                $query->select('id', 'name');
            }
            ])
            ->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Category Not Found');
        }
        return $this->sendResponse('Category Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        // dd($request);
        try {
            $this->beginTransaction();
        $prevImage = $this->category->where('id', $request['id'])->whereNull('deleted_at')->value('image');
        if (isset($request['image'])) {
            if ($request['image'] != '') {
                $path = 'images/categories/' . $prevImage;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
                $image = $request['image'];
                $destinationpath = 'images/categories';
                $categoryImage = date('YmdHis') . '.' . $image->getClientOriginalExtension();
                $image->move($destinationpath, $categoryImage);
                $request['image'] = "$categoryImage";
        }
        $this->updateData($request, 'categories');
        $this->commit();
        return $this->sendResponse('Category Update Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try{
            $this->beginTransaction();
            $id = $this->category->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $prevImage = $this->category->where('id', $request['id'])->whereNull('deleted_at')->first();
            $path = 'images/categories/' . $prevImage['image'];
            if (File::exists($path)) {
                File::delete($path);
            }
            $this->deleteById($request['id'], 'categories');
            $this->commit();
            return $this->sendResponse('Category Delete Success');
        }catch(\Exception $e){
            $this->rollback();
            throw new \Exception($e);
        }

    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterCategory($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Category::where('name', 'like', "%$keyword%")
            ->with(["branch" => function($q){
                $q->select('id','name');
            }])
            ->paginate($rowCount);
        return $this->sendResponse('Category Search Success', $data);
    }
}
