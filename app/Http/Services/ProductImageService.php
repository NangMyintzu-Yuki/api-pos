<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class ProductImageService extends BaseController
{
    public function __construct(private ProductImage $productImage)
    {

    }


    public function store(array $request)
    {
        // dd($request['images']);
        $images = [];
        if ($request['images']) {
            foreach ($request['images'] as $key => $image) {
                $destinationPath = 'images/products/' . $request['product_id'];

                $productImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $productImage);

                $images[] = [
                    "image" => $productImage
                ];
            }
        }

        foreach ($images as $key => $image) {
            $request['images'] = $image['image'];
            $this->insertData($request, 'product_images');
        }
        return $this->sendResponse('Product Images Create Success',$image);


    }


    //////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->productImage->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Product Image Not Found');
        }
        return $this->sendResponse('Product Image Edit Success', $data);
    }


    public function update(array $request)
    {

        $images =[];
        $prevImage = $this->productImage->where('product_id', $request['product_id'])->whereNull('deleted_at')->get();
        // return $prevImage;
        if (isset($request['images'])) {
            foreach($prevImage as $image){
                if ($request['images'] != '') {
                    $path = 'images/products/' .$request['product_id'] ."/". $image['images'];

                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
            }
                foreach ($request['images'] as $key => $image) {
                    $destinationPath = 'images/products/' . $request['product_id'];

                    $productImage = date('YmdHis') . random_int(1 ,999999) . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $productImage);

                    $images[] = [
                        "image" => $productImage
                    ];
                }


        }
        if(count($images) > 0){
            foreach ($images as $image) {
                $request['images'] = $image['image'];
                $this->insertData($request, 'product_images');
            }
        }
        // $this->updateData($request, 'product_images');
        return $this->sendResponse('Product Image Update Success',$images);



    }
    public function filterProductImage($request)
    {
        return "filter";
    }
}
