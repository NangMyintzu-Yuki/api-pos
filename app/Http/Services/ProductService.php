<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductService extends BaseController
{
    public function __construct(
        private Product $product,
        private SaleDetail $saleDetail,
    ) {
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

        ])
            ->orderBy('name', 'asc')
            ->paginate($request['row_count']);
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
            $product['description'] = $request['description'];
            $product['price'] = $request['price'];
            $product['today_menu'] = $request['today_menu'];
            $product['special_menu'] = $request['special_menu'];
            $id =  $this->insertGetId($product, 'products');
            $this->insertProductImageData($request, $id);


            if (isset($request['ingredient_id'])) {
                $this->insertProductIngredientData($request, $id);
            }

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

    public function detail($request)
    {
        $data = $this->product->where('id', $request['id'])
            ->whereNull('deleted_at')
            ->with([
                'branch' => function ($query) {
                    $query->select('id', 'name');
                },
                'category' => function ($query) {
                    $query->select('id', 'name','image');
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
            if (isset($request['ingredient_id'])) {

                $this->insertProductIngredientData($request, $request['id']);
            }

            $product['id'] = $request['id'];
            $product['branch_id'] = $request['branch_id'];
            $product['category_id'] = $request['category_id'];
            $product['name'] = $request['name'];
            $product['description'] = $request['description'];
            $product['price'] = $request['price'];
            $product['today_menu'] = $request['today_menu'];
            $product['special_menu'] = $request['special_menu'];

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

            $saleDetail = $this->saleDetail->where('product_id', $request['id'])->first();
            if ($saleDetail) {
                return $this->sendError("This Product is already used. Can't delete!!");
            }
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
        if (isset($request['keyword']) && isset($request['category_id'])) {
            $data = Product::where('name', 'like', "%$keyword%")
                ->where('category_id', $category)
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'product_image', "ingredients"
                ])
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        } else if ($request['keyword']) {
            $data = Product::where('name', 'like', "%$keyword%")
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'product_image', "ingredients"
                ])
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        } else if ($request['category_id']) {
            $data = Product::where('category_id', $category)
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'product_image', "ingredients"
                ])
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        } else {
            $data = Product::where('name', 'like', "%$keyword%")
                ->orWhere('category_id', $category)
                ->with([
                    'branch' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'product_image', "ingredients"
                ])
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        }
        return $this->sendResponse('Product Search Success', $data);
    }

    public function insertProductImageData($request, $id)
    {
        $images = [];
        $destinationPath = 'images/products/' . $id;
        if (isset($request['today'])) {
            $image = $request['today'];
            $todayImageName = date('YmdHis') . '_today.'  . 'png';
            $image->move($destinationPath, $todayImageName);
            // $tmpImgs[] = "$todayImageName";
            $productImage['product_id'] = $id;
            $productImage['category_id'] = $request['category_id'];
            $productImage['branch_id'] = $request['branch_id'];
            $productImage['images'] = "$todayImageName";
            $productImage['today_menu'] = 1;

            $this->insertData($productImage, 'product_images');
        }
        if (isset($request['special'])) {
            $image = $request['special'];
            $specialImageName = date('YmdHis') . '_special.'  . 'png';
            $image->move($destinationPath, $specialImageName);
            $specialProduct['product_id'] = $id;
            $specialProduct['category_id'] = $request['category_id'];
            $specialProduct['branch_id'] = $request['branch_id'];
            $specialProduct['images'] = "$specialImageName";
            $specialProduct['special_menu'] = 1;

            $this->insertData($specialProduct, 'product_images');
        }

        if (count($request['image']) > 0) {
            foreach ($request['image'] as $key => $image) {
                $productImage = date('YmdHis') . '_' . random_int(1, 99) . "."  . 'png';
                $image->move($destinationPath, $productImage);
                $images[] =  [
                    "image" => $productImage
                ];
            }
        }

        // Product Images
        foreach ($images as $image) {
            $product['product_id'] = $id;
            $product['category_id'] = $request['category_id'];
            $product['branch_id'] = $request['branch_id'];
            $product['images'] = $image['image'];

            $this->insertData($product, 'product_images');
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
