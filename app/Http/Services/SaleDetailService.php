<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\SaleDetailResource;
use App\Models\SaleDetail;

class SaleDetailService extends BaseController
{
    public function __construct(private SaleDetail $saleDetail)
    {
    }


    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {

        // $users = User::with(['posts' => function ($query) {
        //     $query->select('id', 'user_id', 'title', 'created_at');
        // }])->get();

        // retrive specific columns from  a single related model
        $data = SaleDetail::with([
                                'sale' => function($query){
                                    $query->select('id','voucher_no','total_amount');
                                },
                                'product' =>function($query){
                                    $query->select('id','category_id','name','image');
                                }
                            ])
                            ->paginate($request['row_count']);

        return $this->sendResponse('Sale Detail Index Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'sale_details');
        return $this->sendResponse('Sale Detail Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {

        $data = $this
                ->saleDetail
                ->where('id', $request['id'])
                ->whereNull('deleted_at')
                ->with(['sale' => function ($query){
                    $query->select('id','voucher_no','total_amount');
                },
                'product' => function($query){
                    $query->select('id','category_id','name','image');
                }
                ])
                ->first();
        if (!$data) {
            return $this->sendResponse('Sale Detail Not Found');
        }
        return $this->sendResponse('Sale Detail Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request, 'sale_details');
        return $this->sendResponse('Sale Detail Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        $this->deleteById($request['id'], 'sale_details');
        return $this->sendResponse('Sale Detail Delete Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterSaleDetail($request)
    {
        $rowCount = $request['row_count'];
        $saleId =  $request['sale_id'];
        $productId =  $request['product_id'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = SaleDetail::where('product_id', $productId)
                            ->where('sale_id', $saleId)
                            ->with(['sale' => function ($query){
                                $query->select('id','voucher_no','total_amount');
                            },
                            'product' => function($query){
                                $query->select('id','category_id','name','image');
                            }
                            ])
                            ->paginate($rowCount);

        return $this->sendResponse('Sale Detail Search Success', $data);
    }
}
