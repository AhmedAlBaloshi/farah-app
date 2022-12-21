<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\ServiceList;

class ServiceListController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ServiceList::with('service');
        
        if($request->service_id){
            $query->where('service_id', $request->service_id);
        }
        $serviceList = $query->latest()->paginate(10);

        if ($serviceList) {
            return response()->json([
                'success' => 1,
                'service_lists' => $serviceList
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load service list from database'
        ], 404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'image'              => 'required',
            'service_id'         => 'required'
        ]);

        $serviceList = ServiceList::add($request->all());
        if ($serviceList) {
            return response()->json([
                'success' => 1,
                'message' => 'Service list added successfully',
                "service_list_id" => $serviceList->service_list_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add service list'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serviceList  = ServiceList::where('service_list_id', $id)->first();
        if ($serviceList) {
            return response()->json([
                'success' => 1,
                'service_list' => $serviceList
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Service list not found'
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'service_id'         => 'required'
        ]);

        $service = ServiceList::updateRecords($id, $request->all());
        if (!$service) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Service list not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Service list updated successfully',
            "service_list_id" => $service->service_list_id
        ], 200);
    }


    public function getServiceList()
    {
        $service = ServiceList::pluck('service_name', 'service_list_id')->toArray();
        return response()->json([
            'success' => 1,
            'services' => $service
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {

            $service = ServiceList::where('service_list_id', $id)->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Service list deleted successfully',
            ], 200);
        }
    }
}
