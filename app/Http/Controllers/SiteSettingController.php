<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteSettingRequest;
use App\Http\Requests\UpdateSiteSettingRequest;
use App\Http\Services\SiteSettingService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(private SiteSettingService $siteSettingService)
    {
    }
    public function index(Request $request)
    {
        return $this->siteSettingService->index($request);
    }
    public function store(StoreSiteSettingRequest $request)
    {
        return $this->siteSettingService->store($request->validated());
    }
    public function edit(Request $request)
    {
        return $this->siteSettingService->edit($request);
    }
    public function update(UpdateSiteSettingRequest $request)
    {
        return $this->siteSettingService->update($request->validated());
    }
    public function delete(Request $request)
    {
        return $this->siteSettingService->delete($request);
    }
    public function filter(Request $request)
    {
        return $this->siteSettingService->filter($request);
    }

}
