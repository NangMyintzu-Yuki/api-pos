<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\City;
use App\Models\Division;
use Carbon\Carbon;

class DivisionService extends BaseController
{
    public function __construct(
        private Division $division,
        private City $city
        )
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////


    public function index($request)
    {
        $data = $this->getAll('divisions', $request['row_count'], 'name', 'asc');
        return $this->sendResponse('Division Index Success', $data);
    }


    ////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {

        $request['created_at'] = Carbon::now();
        $this->insertData($request, 'divisions');
        return $this->sendResponse('Division Create Success');
    }


    ///////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->division->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Division Not Found");
        }
        return $this->sendResponse('Division Edit Success', $data);
    }


    ///////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'divisions');
        return $this->sendResponse('Division Update Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        try {
            $this->beginTransaction();
            $id = $this->division->find($request['id']);
            if (!$id) {
                return $this->sendError("No Record to Delete");
            }
            $division = $this->city->where('division_id', $request['id'])->first();
            if ($division) {
                return $this->sendError("This Division is already used. Can't delete!!");
            }
            $this->deleteById($request['id'], 'divisions');
            $this->commit();
            return $this->sendResponse('Division Delete Success');
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e);
        }
    }


    ///////////////////////////////////////////////////////////////////////////


    public function filter($request)
    {
        $keyword = $request['keyword'];
        $rowCount = $request['row_count'];
        if (!$rowCount) {
            $data = $this->division->where('name', 'like', "%$keyword%")
                ->orderBy('name', 'asc')
                ->paginate();
        } else {

            $data = $this->division->where('name', 'like', "%$keyword%")
                ->orderBy('name', 'asc')
                ->paginate($rowCount);
        }
        return $this->sendResponse('Division Search Success', $data);
    }

    /////////////////////////////////////////////////////////////////////////////////

}
