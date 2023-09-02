<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductService extends BaseController
{
    public function __construct(private Product $product)
    {
    }

    ///////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = Product::with([
            'branch' => function ($query) {
                $query->select('id', 'name');
            },
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'product_image', "ingredients"

        ])->paginate($request['row_count']);
        return $this->sendResponse('Product Index Success', $data);
    }

    //////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        // dd($request);
        try {
            $this->beginTransaction();
            //Single Image
            // if ($image = $request['image']) {
            //     $destinationPath = 'images/products';
            //     $productImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            //     $image->move($destinationPath, $productImage);
            //     $request['image'] = "$productImage";
            // }


            $product['branch_id'] = $request['branch_id'];
            $product['category_id'] = $request['category_id'];
            $product['name'] = $request['name'];
            $product['price'] = $request['price'];
            $id =  $this->insertGetId($product, 'products');
            $this->insertProductImageData($request, $id);


            $this->insertProductIngredientData($request, $id);

            $this->commit();
            // return $images;
            return $this->sendResponse('Product Create Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }


    //////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->product->where('id', $request['id'])
            ->whereNull('deleted_at')
            ->with([
                'branch' => function ($query) {
                    $query->select('id', 'name');
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                }, 'product_image', "ingredients"
            ])
            ->first();
        if (!$data) {
            return $this->sendResponse('Product Not Found');
        }
        return $this->sendResponse('Product Edit Success', $data);
    }

    //////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {

        try {
            $this->beginTransaction();

            $multiImagePath = "images/products/" . $request['id'];
            File::deleteDirectory(public_path($multiImagePath));


            DB::table('product_images')->where('product_id', $request['id'])->delete();
            DB::table('product_ingredients')->where('product_id', $request['id'])->delete();

            $this->insertProductImageData($request, $request['id']);
            $this->insertProductIngredientData($request, $request['id']);

            $product['id'] = $request['id'];
            $product['branch_id'] = $request['branch_id'];
            $product['category_id'] = $request['category_id'];
            $product['name'] = $request['name'];
            $product['price'] = $request['price'];

            $this->updateData($product, 'products');
            $this->commit();
            return $this->sendResponse('Product Update Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    //////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try {
            $this->beginTransaction();
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

            DB::table('product_images')->where('product_id', $request['id'])->delete();
            DB::table('product_ingredients')->where('product_id', $request['id'])->delete();

            // soft delete
            // ProductImage::where('product_id',$request['id'])->delete();
            // ProductIngredient::where('product_id',$request['id'])->delete();

            $this->deleteById($request['id'], 'products');

            $this->commit();
            return $this->sendResponse('Product Delete Success', $multiImagePath);
        } catch (\Exception $e) {
            $this->rollback();
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
        $data = Product::where('name', 'like', "%$keyword%")
            ->orWhere('category_id', $category)
            ->with([
                'branch' => function ($query) {
                    $query->select('id', 'name');
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->paginate($rowCount);
        return $this->sendResponse('Product Search Success', $data);
    }

    public function insertProductImageData($request, $id)
    {
        $images = [];
        $destinationPath = 'images/products/' . $id;
        if (count($request['image']) > 0) {
            foreach ($request['image'] as $key => $image) {
                $productImage = date('YmdHis') . '_' . random_int(1, 99) . "."  . $image->getClientOriginalExtension();
                $image->move($destinationPath, $productImage);
                $images[] =  [
                    "image" => $productImage
                ];
            }
        }

        // Product Images
        foreach ($images as $key => $image) {
            $porductImage['product_id'] = $id;
            $porductImage['category_id'] = $request['category_id'];
            $porductImage['branch_id'] = $request['branch_id'];
            $porductImage['images'] = $image['image'];
            $this->insertData($porductImage, 'product_images');
        }
    }
    public function insertProductIngredientData($request, $id)
    {
        foreach ($request['ingredient_id'] as $key => $ingredient) {
            $ingredients[] = [
                "ingredient_id" => $ingredient
            ];
        }
        // Product Ingredient
        foreach ($ingredients as $key => $ingredient) {
            $productIngredient['product_id'] = $id;
            // $this->size->where('id', $data['size_id'])->value('name')
            $productIngredient['ingredient_id'] = Ingredient::where('name', $ingredient)->value('id');
            $productIngredient['status'] = 1;
            $this->insertData($productIngredient, 'product_ingredients');
        }
    }
}
