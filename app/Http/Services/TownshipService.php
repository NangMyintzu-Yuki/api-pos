<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Township;
use Carbon\Carbon;

class TownshipService extends BaseController
{
    public function __construct(private Township $township)
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////



    public function index($request)
    {
        $data = Township::with([
            "city" => function ($q) {
                $q->select(
                    'id',
                    'name',
                );
            },
        ])->orderBy('name', 'asc')->paginate($request['row_count']);
        return $this->sendResponse('Township Index Success', $data);
    }


    ////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {

        $request['created_at'] = Carbon::now();
        $this->insertData($request, 'townships');
        return $this->sendResponse('Township Create Success');
    }


    ///////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->township->where('id', $request['id'])->whereNull('deleted_at')->with([
            "city" => function ($q) {
                $q->select(
                    'id',
                    'name',
                );
            },
        ])->first();
        if (!$data) {
            return $this->sendResponse("Township Not Found");
        }
        return $this->sendResponse('Township Edit Success', $data);
    }


    ///////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'townships');
        return $this->sendResponse('Township Update Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        $id = $this->township->find($request['id']);
        if (!$id) {
            return $this->sendError("No Record to Delete");
        }
        $this->deleteById($request['id'], 'townships');
        return $this->sendResponse('Township Delete Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function filter($request)
    {
        $keyword = $request['keyword'];
        $city = isset($request['city']) ? $request['city'] : '';
        $rowCount = $request['row_count'];
        if (!$rowCount) {
            $data = $this->township->where('name', 'like', "%$keyword%")
                ->with(['city' => function ($q) {
                    $q->select("id", "name");
                }])
                ->orderBy('name', 'asc')
                ->paginate();
        } else {

                $data = $this->township->where('name', 'like', "%$keyword%")->where('city_id',$city)
                    ->with(['city' => function ($q) {
                        $q->select("id", "name");
                    }])
                    ->orderBy('name', 'asc')
                    ->paginate($rowCount);

        }
        return $this->sendResponse('Township Search Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////

}
