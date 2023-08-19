<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductService extends BaseController
{
    public function __construct(private Product $product)
    {

    }

    ///////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Product::with(['branch' => function ($query){
            $query->select('id','name');
        },
        'category' => function($query){
            $query->select('id','name');
        }
        ])->paginate($request['row_count']);
        return $this->sendResponse('Product Index Success',$data);
    }

    //////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        if ($image = $request['image']) {
            $destinationPath = 'images/products';
            $productImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $productImage);
            $request['image'] = "$productImage";
        }

        $this->insertData($request,'products');
        return $this->sendResponse('Product Create Success');
    }


    //////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->product->where('id',$request['id'])
            ->whereNull('deleted_at')
            ->with(['branch' => function ($query) {
                $query->select('id', 'name');
            },
            'category' => function ($query){
                $query->select('id','name');
            }
            ])
            ->first();
        if(!$data){
            return $this->sendResponse('Product Not Found');
        }
        return $this->sendResponse('Product Edit Success',$data);
    }

    //////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {

        $prevImage = $this->product->where('id', $request['id'])->whereNull('deleted_at')->value('image');
        if(isset($request['image'])){
            if($request['image'] != ''){
                $path = 'images/products/' .$prevImage;
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            $image = $request['image'];
            $destinationPath = 'images/products';
            $productImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $productImage);
            $request['image'] = "$productImage";
        }
        $this->updateData($request,'products');
        return $this->sendResponse('Product Update Success');
    }

    //////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try{
            $this->product->beginTransation();
            $id = $this->product->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $prevImage = $this->product->where('id', $request['id'])->whereNull('deleted_at')->first();
            $multiImagePath = "images/products/" . $request['id'];
            File::deleteDirectory(public_path($multiImagePath));

            $path = 'images/products/' . $prevImage['image'];
            if (File::exists($path)) {
                File::delete($path);
            }
            $this->deleteById($request['id'],'products');

            $this->product->commit();
            return $this->sendResponse('Product Delete Success', $multiImagePath);
        } catch (\Exception $e) {
            $this->product->rollBack();
            throw new \Exception($e);
        }
    }

    //////////////////////////////////////////////////////////////////////

    public function filterProduct($request)
    {
        $keyword = $request['keyword'];
        $category = $request['category_id'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Product::where('name','like',"%$keyword%")
            ->orWhere('category_id',$category)
            ->with([
                'branch' => function ($query) {
                    $query->select('id', 'name');
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->paginate($rowCount);
        return $this->sendResponse('Product Search Success',$data);
    }
}

