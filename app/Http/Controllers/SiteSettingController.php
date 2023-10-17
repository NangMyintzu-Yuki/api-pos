<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteSettingRequest;
use App\Http\Requests\UpdateSiteSettingRequest;
use App\Http\Services\SiteSettingServiceService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(private SiteSettingServiceService $siteSettingServiceService)
    {
    }
    public function index(Request $request)
    {
        return $this->siteSettingServiceService->index($request);
    }
    public function store(StoreSiteSettingRequest $request)
    {
        return $this->siteSettingServiceService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->siteSettingServiceService->edit($request);
    }
    public function update(UpdateSiteSettingRequest $request)
    {
        return $this->siteSettingServiceService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->siteSettingServiceService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->siteSettingServiceService->filter($request);
    }

}
