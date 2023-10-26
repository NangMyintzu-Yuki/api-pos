<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LocationService extends BaseController
{
    public function __construct(
        private Location $location,

    ) {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('locations', $request['row_count'],'id','asc');

        return $this->sendResponse('Location Index Success', $data);
    }
    public function store(array $request)
    {

        $params['user_id'] = $request['user_id'] ?? null;
        $params['title'] = $request['title'] ?? null;
        $params['address'] = $request['address'] ?? null;
        $params['township_id'] = $request['township_id'] ?? null;
        $params['latitude'] = $request['latitude'] ?? null;
        $params['longitude'] = $request['longitude'] ?? null;
        $params['created_at'] = Carbon::now();

        $check = $this->location->where('user_id',$request['user_id'])->whereNull('deleted_at')->first();

        if($check == null){
            $params['is_default'] = 1;
        }else{
            $params['is_default'] = 0;
        }
        DB::table('locations')->insert($params);

        return $this->sendResponse('User Location Saved Success');
    }

    public function getLocationWithUserId($request)
    {
        info($request);
        $data = $this
            ->location
            ->where('user_id', $request['id'])
            ->whereNull('deleted_at')
            ->get();
            info($data);
        if (count($data) == 0) {
            return $this->sendResponse('Your Location Not Found');
        }
        return $this->sendResponse('Your Location Edit Success', $data);
    }


    public function changeStatus($request)
    {

        $userId = $this->location->where('id',$request['id'])->value('user_id');
        $this->location->where('user_id', $userId)->update(['is_default'=>0]);


        $updateData['id'] = $request['id'];
        $updateData['is_default'] = 1;
        $this->location->where('id', $updateData['id'])->update($updateData);

        $data = $this->location->where('user_id',$userId)->whereNull('deleted_at')->get();

        return $this->sendResponse('Your Location Successfully Change', $data);
    }
    public function destory($request)
    {
        $id = $this->location->find($request['id']);
        if (!$id) {
            return $this->sendError("No Record to Delete");
        }
        $params['deleted_at'] = Carbon::now();
        $query = DB::table('locations')->where('id', $request['id'])->update($params);
        return $this->sendResponse('Location Delete Success');
    }
}
