<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Table;

class BranchService extends BaseController{
    public function __construct(
        private Branch $branch,
        private Product $product,
        private Category $category,
        private Operator $operator,
        private Kitchen $kitchen,
        private Table $table,
        private Payment $payment,
        )
    {

    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('branches',$request['row_count']);
        return $this->sendResponse('Branch Index Success',$data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request,'branches');
        return $this->sendResponse('Branch Create Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->branch->where('id',$request['id'])->whereNull('deleted_at')->first();
        if(!$data)
        {
            return $this->sendResponse('Branch Not Found');
        }
        return $this->sendResponse('Branch Edit Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function update(array $request)
    {
        $this->updateData($request,'branches');
        return $this->sendResponse('Branch Update Success');
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function delete($request)
    {
        try {
            $this->beginTransaction();
            //code...
            $id = $this->branch->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $product = $this->product->where('branch_id',$request['id'])->first();
            $category = $this->category->where('branch_id',$request['id'])->first();
            $operator = $this->operator->where('branch_id',$request['id'])->first();
            $kitchen = $this->kitchen->where('branch_id',$request['id'])->first();
            $table = $this->table->where('branch_id',$request['id'])->first();
            $payment = $this->payment->where('branch_id',$request['id'])->first();
            if($product || $category || $operator || $kitchen || $table || $payment){
                return $this->sendError("This Branch has already used. Can't delete!!");
            }
            $this->deleteById($request['id'],'branches');
            $this->commit();
            return $this->sendResponse('Branch Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function filterBranch($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        $rowCount = !$rowCount ? null : $rowCount;
        $data = Branch::where('name','like',"%$keyword%")->paginate($rowCount);
        return $this->sendResponse('Branch Search Success',$data);
    }
}
