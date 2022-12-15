<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Service;


class ServiceController extends BaseController
{
    public function index(Request $request)
    {
        $services = Service::latest()->paginate(10);

        if ($services) {
            return response()->json([
                'success' => 1,
                'services' => $services
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load services from database'
        ], 404);
    }

    public function getServices()
    {
        $service = Service::pluck('service_name', 'service_id')->toArray();
        return response()->json([
            'success' => 1,
            'services' => $service
        ], 200);
    }

    public function show($id)
    {
        $service  = Service::where('service_id', $id)->first();

        if ($service) {
            return response()->json([
                'success' => 1,
                'service' => $service
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Service not found'
        ], 404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'image'              => 'required'
        ]);

        $service = Service::add($request->all());

        if ($service) {
            return response()->json([
                'success' => 1,
                'message' => 'Service added successfully',
                "service_id" => $service->service_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add service'
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
        ]);

        $service = Service::updateRecords($id, $request->all());
        if (!$service) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Service not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Service updated successfully',
            "service_id" => $service->service_id
        ], 200);
    }

    public function destroy($id)
    {
        if ((int)$id > 0) {

            $service = Service::where('service_id', $id)->delete();
          
            return response()->json([
                'success' => 1,
                'message' => 'Service deleted successfully',
            ], 200);
        }
    }
}
