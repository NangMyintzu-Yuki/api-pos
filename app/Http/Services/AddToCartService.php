<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\AddToCart;

class AddToCartService extends BaseController
{
    public function __construct(private AddToCart $addToCart)
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = AddToCart::with(['user'])->paginate($request['row_count']);
        return $this->sendResponse('Add To Cart Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'add_to_carts');
        return $this->sendResponse('Successfully Added');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->addToCart->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse('Data Not Found');
        }
        return $this->sendResponse('Add To Cart Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'add_to_carts');
        return $this->sendResponse('Add To Cart Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'add_to_carts');
        return $this->sendResponse('Add To Cart Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterAddToCart($request)
    {
        $rowCount = $request['row_count'];
        $userId =  $request['user_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = AddToCart::where('user_id', $userId)->paginate($rowCount);

        return $this->sendResponse('Add To Cart Search Success', $data);
    }
}
