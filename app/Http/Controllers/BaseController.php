<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{


    ///////////////////////////////////////////////////////////////////

    public function sendResponse($message, $data = null)
    {
        $response = [
            "code" => 200,
            "status" => "Success",
            "message" => $message,
        ];
        if ($data) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }

    ///////////////////////////////////////////////////////////////////

    public function sendError($message)
    {
        $response = [
            "code" => 400,
            "status" => "failed",
            "message" => $message,
        ];
        return response()->json($response);
    }

    ////////////////////////////////////////////////////////////////////


    public function getAll($model,$rowCount)
    {
        $rowCount = !$rowCount ? 20 : $rowCount;
        $data = DB::table($model)->whereNull('deleted_at')->paginate($rowCount);
        // return $data->items();
        return $data;
    }


    ////////////////////////////////////////////////////////////////////


    public function insertData(array $params, $modal)
    {
        // $params['created_by'] = request()->name;
        $params['created_at'] = Carbon::now();
        $query = DB::table($modal)->insert($params);
        return $query;
    }


    ////////////////////////////////////////////////////////////////////


    public function insertGetId(array $params, $modal)
    {
        // $params['created_by'] = request()->name;
        $params['created_at'] = Carbon::now();
        $id = DB::table($modal)->insertGetId($params);
        return $id;
    }


    ///////////////////////////////////////////////////////////////////


    public function updateData(array $params, $modal)
    {
        $params['updated_at'] = Carbon::now();
        $query = DB::table($modal)->where('id',$params['id'])->update($params);
        return $query;
    }


    ///////////////////////////////////////////////////////////////////


    public function deleteById($id, $modal)
    {
        // $params['deleted_by'] = ;
        $params['deleted_at'] = Carbon::now();
        $query = DB::table($modal)->where('id', $id)->update($params);
        return $query;
    }


    ///////////////////////////////////////////////////////////////////


    public function forceDeleteById($id, $modal)
    {
        // $params['deleted_by'] = ;
        $params['deleted_at'] = Carbon::now();
        $query = DB::table($modal)->where('id',$id)->delete();
        return $query;
    }


}
