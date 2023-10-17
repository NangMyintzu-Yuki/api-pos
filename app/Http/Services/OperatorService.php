<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\OperatorResource;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Kitchen;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OperatorService extends BaseController
{
    public function __construct(
        private Operator $operator,
        private Branch $branch,
        private Category $category,
        private Ingredient $ingredient,
        private Kitchen $kitchen,
        private PaymentType $paymentType,
        private Payment $payment,
        private Table $table,
        private Product $product,
        private Sale $sale,
        private User $user,
    ) {
    }

    /////////////////////////////////////////////////////////////////////////////////////


    public function index($request)
    {
        // $data = $this->getAll('operators',$request['row_count']);
        // $data = $this->operator->get();
        // $data = OperatorResource::collection($data->items())->response()->getData(true);

        $data = Operator::with(['branch' => function ($q) {
                $q->select('id', 'name');
            },
            "role" => function($q){
                $q->select('id','name');
            }
            ])
            ->orderBy('name','asc')
            ->paginate($request['row_count']);
        return $this->sendResponse('Operator Index Success', $data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function store(array $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['created_at'] = Carbon::now();
        $this->insertData($request, 'operators');
        return $this->sendResponse('Operator Create Success');
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function edit($request)
    {
        $data = $this
            ->operator
            ->where('id', $request['id'])
            ->with(['branch' => function ($q) {
                $q->select('id', 'name');
                },
            "role" => function ($q) {
                $q->select('id', 'name');
            }
            ])
            ->first();
        if (!$data) {
            return $this->sendResponse("There is no data with");
        }
        // $data = new OperatorResource($data);
        return $this->sendResponse('Operator Edit Success', $data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'operators');
        return $this->sendResponse('Operator Update Success');
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        try {
            $this->beginTransaction();
            $id = $this->operator->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }

            $branch = $this->branch->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $category = $this->category->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $ingredient = $this->ingredient->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $paymentType = $this->paymentType->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $payment = $this->payment->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $table = $this->table->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $product = $this->product->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $sale = $this->sale->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $kitchen = $this->kitchen->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();
            $user = $this->user->where('created_by', $request['id'])->where('updated_by', $request['id'])->where('deleted_by', $request['id'])->first();

            if ($branch || $category || $ingredient || $payment || $paymentType || $table || $product || $sale || $kitchen || $user) {
                return $this->sendError("This Operator is already used. Can't delete!!");
            }

            $this->deleteById($request['id'], 'operators');
            $this->commit();
            return $this->sendResponse('Operator Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }



    /////////////////////////////////////////////////////////////////////////////////////


    public function filterOperator($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        if (!$rowCount) {
            $data = Operator::with('branch')
                ->where('name', 'like', "%$keyword%")
                ->orwhere('username', 'link', "%$keyword%")
                ->with(['branch' => function ($q) {
                    $q->select('id', 'name');
                },
                "role" => function ($q) {
                    $q->select('id', 'name');
                }])
                ->orderBy('name','asc')
                ->paginate();
        } else {
            $data = Operator::with('branch')
                ->where('name', 'like', "%$keyword%")
                ->orwhere('username', 'link', "%$keyword%")
                ->with(['branch' => function ($q) {
                    $q->select('id', 'name');
                },
                "role" => function ($q) {
                    $q->select('id', 'name');
                }])
                ->orderBy('name','asc')
                ->paginate($rowCount);
        }
        return $this->sendResponse('Operator Search Success', $data);
    }


    /////////////////////////////////////////////////////////////////////////////////////


    public function changePassword(array $request)
    {
        // dd($request);

        $operatorId = $request['operator_id'];
        $operatorInfo = $this->operator->where('id', $operatorId)->first();
        if (!$operatorInfo || !Hash::check($request['old_password'], $operatorInfo->password)) {
            return $this->sendError(("Password Incorrect!"));
        }
        $operatorInfo->update([
            'password' => Hash::make($request['new_password']),
            // 'updated_by' => auth()->user()->name,
            'updated_at' => Carbon::now(),
        ]);
        return $this->sendResponse('Operator Password Change Success');
    }
}
