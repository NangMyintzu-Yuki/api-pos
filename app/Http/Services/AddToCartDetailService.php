<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\AddToCartDetail;

class AddToCartDetailService extends BaseController
{
    public function __construct(private AddToCartDetail $addToCartDetail)
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = AddToCartDetail::with([ 'product'])->paginate($request['row_count']);
        return $this->sendResponse('Add To Cart Detail Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'add_to_cart_details');
        return $this->sendResponse('Add To Cart Detail Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->addToCartDetail->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Data Not Found');
        }
        return $this->sendResponse('Add To Cart Detail Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'add_to_cart_details');
        return $this->sendResponse('Add To Cart Detail Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'add_to_cart_details');
        return $this->sendResponse('Add To Cart Detail Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterAddToCartDetail($request)
    {
        $rowCount = $request['row_count'];
        $addToCartId =  $request['add_to_cart_id'];
        $productId =  $request['product_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = AddToCartDetail::where('add_to_cart_id', $addToCartId)->where('product_id', $productId)->paginate($rowCount);

        return $this->sendResponse('Add To Cart Detail Search Success', $data);
    }
}
