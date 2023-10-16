<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LocationService extends BaseController
{
    public function __construct(
        private Location $dashboard,

    ) {
    }

    /////////////////////////////////////////////////////////////////////////////////////

    public function index($request)
    {
        $data = $this->getAll('locations', $request['row_count']);

        return $this->sendResponse('Location Index Success', $data);
    }
    public function store(array $request)
    {

        if(isset($request['user_id'])){
            $params['user_id'] = $request['user_id'];
        }
        $params['latitude'] = $request['latitude'];
        $params['longitude'] = $request['longitude'];
        $params['created_at'] = Carbon::now();
         DB::table('locations')->insert($params);

        return $this->sendResponse('User Location Saved Success');
    }
}
