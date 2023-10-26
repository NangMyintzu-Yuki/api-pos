<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperatorResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Str;

class BaseController extends Controller
{



    // public function handle()
    // {
    //     $files = Arr::where(Storage::disk('log')->files(), function ($filename) {
    //         return Str::endsWith($$filename, '.log');
    //     });


    //     $count = count($files);

    //     if (Storage::disk('log')->delete($files)) {
    //         $this->info(sprintf('Deleted %s %s!', $count, Str::plural('file', $count)));
    //     } else {
    //         $this->error('Error in deleting log files!');
    //     }
    // }
    ///////////////////////////////////////////////////////////////////

    public function sendResponse($message, $data = null)
    {
        $response = [
            "code" => 200,
            "status" => "Success",
            "message" => $message,
        ];
        if ($data) {

            // $response[] = [
            //     "data"=>$data['data']
            // ];
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


    public function getAll($model,$rowCount,$sortingName = 'name', $sortingType = 'asc')
    {
        $rowCount = !$rowCount ? 20 : $rowCount;
        $data = DB::table($model)->whereNull('deleted_at')->orderBy($sortingName,$sortingType)->paginate($rowCount);
        // return $data->items();
        return $data;
    }


    ////////////////////////////////////////////////////////////////////


    public function insertData(array $params, $modal)
    {
        $params['created_by'] = auth()->user()->id;
        $params['created_at'] = Carbon::now();
        $query = DB::table($modal)->insert($params);
        return $query;
    }


    ////////////////////////////////////////////////////////////////////


    public function insertGetId(array $params, $modal)
    {
        $params['created_by'] = auth()->user()->id;
        $params['created_at'] = Carbon::now();
        $id = DB::table($modal)->insertGetId($params);
        return $id;
    }


    ///////////////////////////////////////////////////////////////////


    public function updateData(array $params, $modal)
    {
        // info(auth()->user()->id);
        $params['updated_by'] = auth()->user()->id;

        $params['updated_at'] = Carbon::now();
        $query = DB::table($modal)->where('id',$params['id'])->update($params);
        return $query;
    }


    ///////////////////////////////////////////////////////////////////


    public function deleteById($id, $modal)
    {
        $params['deleted_by'] = auth()->user()->id;
        $params['deleted_at'] = Carbon::now();
        $query = DB::table($modal)->where('id', $id)->update($params);
        return $query;
    }


    ///////////////////////////////////////////////////////////////////


    public function deletedByAttr($attr, $val, $modal)
    {
        $params['deleted_by'] = auth()->user()->id;
        $params['deleted_at'] = Carbon::now();
        $query = DB::table($modal)->where($attr,$val)->update($params);
        return $query;
    }


    public function forceDeleteById($id, $modal)
    {
        $params['deleted_by'] = auth()->user()->id;
        $params['deleted_at'] = Carbon::now();
        $query = DB::table($modal)->where('id',$id)->delete();
        return $query;
    }


    ///////////////////////////////////////////////////////////////////////


    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function rollback()
    {
        DB::rollBack();
    }

    public function commit()
    {
        DB::commit();
    }


}
