<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\SiteSetting;
use Carbon\Carbon;

class SiteSettingService extends BaseController
{
    public function __construct(private SiteSetting $siteSetting)
    {
    }

    /////////////////////////////////////////////////////////////////////////////////////



    public function index($request)
    {
        $data = $this->siteSetting->first();
        return $this->sendResponse('Site Setting Index Success', $data);
    }


    ////////////////////////////////////////////////////////////////////////////

    public function store(array $request)
    {
        $this->insertData($request, 'site_settings');
        return $this->sendResponse('Site Setting Create Success');
    }


    ///////////////////////////////////////////////////////////////////////////

    public function edit($request)
    {
        $data = $this->siteSetting->where('id', $request['id'])->whereNull('deleted_at')->first();
        if (!$data) {
            return $this->sendResponse("Site Setting Not Found");
        }
        return $this->sendResponse('Site Setting Edit Success', $data);
    }


    ///////////////////////////////////////////////////////////////////////////


    public function update(array $request)
    {
        $this->updateData($request, 'site_settings');
        return $this->sendResponse('Site Setting Update Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function delete($request)
    {
        $id = $this->siteSetting->find($request['id']);
        if (!$id) {
            return $this->sendError("No Record to Delete");
        }
        $this->deleteById($request['id'], 'site_settings');
        return $this->sendResponse('Site Setting Delete Success');
    }


    ///////////////////////////////////////////////////////////////////////////


    public function filter($request)
    {

        return $this->sendResponse('Township Search Success');
    }

    /////////////////////////////////////////////////////////////////////////////////

}
