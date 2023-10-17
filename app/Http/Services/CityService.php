<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\City;
use App\Models\Division;
use App\Models\Township;
use Carbon\Carbon;

class CityService extends BaseController
{
    public function __construct(
        private City $city,
        private Township $township
        )
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////



    public function index($request)
    {
        $data = City::with([
            "division" => function ($q) {
                $q->select(
                    'id',
                    'name',
                );
            },
        ])->orderBy('name', 'asc')->paginate($request['row_count']);
        return $this->sendResponse('City Index Success', $data);
    }


    ////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {

        $request['created_at'] = Carbon::now();
        $this->insertData($request, 'cities');
        return $this->sendResponse('City Create Success');
    }


    ///////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->city
                    ->where('id', $request['id'])
                    ->whereNull('deleted_at')
                    ->with([
                        "division" => function ($q) {
                            $q->select(
                                'id',
                                'name',
                            );
                        },
                    ])
                    ->first();
        if (!$data) {
            return $this->sendResponse("City Not Found");
        }
        return $this->sendResponse('City Edit Success', $data);
    }


    ///////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'cities');
        return $this->sendResponse('City Update Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        try {
            $this->beginTransaction();
            $id = $this->city->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $city = $this->township->where('city_id', $request['id'])->first();
            if ($city) {
                return $this->sendError("This City is already used. Can't delete!!");
            }
            $this->deleteById($request['id'], 'cities');
            $this->commit();
            return $this->sendResponse('City Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }


    ///////////////////////////////////////////////////////////////////////////


    public function filter($request)
    {
        $keyword = $request['keyword'];
        $division = isset($request['division']) ? $request['division'] : '';
        $rowCount = $request['row_count'];

        if (!$rowCount) {
            $data = $this->city->where('name', 'like', "%$keyword%")
                ->with(['division' => function ($q) {
                    $q->select("id", "name");
                }])
                ->orderBy('name', 'asc')
                ->paginate();
        } else {
            $data = $this->city->where('name', 'like', "%$keyword%")->where('division_id', $division)
                ->with(['division' => function ($q) {
                    $q->select("id", "name");
                }])
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        }
        return $this->sendResponse('City Search Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////

}
