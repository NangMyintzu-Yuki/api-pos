<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService)
    {
    }
    public function index(Request $request)
    {
        return $this->locationService->index($request);
    }
    public function store(StoreLocationRequest $request)
    {
        return $this->locationService->store($request->validated());
    }
    public function getLocationWithUserId(Request $request)
    {
        return $this->locationService->getLocationWithUserId($request);
    }
    public function changeStatus(Request $request)
    {
        return $this->locationService->changeStatus($request);
    }
    public function destory(Request $request)
    {
        return $this->locationService->destory($request);
    }
}
