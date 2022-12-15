<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubService;

class SubServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_services = SubService::with('service')->latest()->paginate(10);

        if ($sub_services) {
            return response()->json([
                'success' => 1,
                'sub_services' => $sub_services
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load sub services list from database'
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
            'sub_service_name'    => 'required',
            'sub_service_name_ar' => 'required',
            'service_list_id'     => 'required'
        ]);

        $sub_services = SubService::add($request->all());

        if ($sub_services) {
            return response()->json([
                'success' => 1,
                'message' => 'Sub service list added successfully',
                "sub_service_id" => $sub_services->sub_service_id
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add sub service list'
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
        $service  = SubService::where('sub_service_id', $id)->first();

        if ($service) {
            return response()->json([
                'success' => 1,
                'sub_service_list' => $service
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, sub Service list not found'
        ], 404);
    }
    
    public function getSubService()
    {
        $service = SubService::pluck('sub_service_name', 'sub_service_id')->toArray();
        return response()->json([
            'success' => 1,
            'services' => $service
        ], 200);
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
        $this->validate($request,[
            'sub_service_name'       => 'required',
            'sub_service_name_ar'    => 'required',
            'service_list_id'         => 'required'
        ]);

        $sub_service = SubService::updateRecords($id,$request->all());

        if (!$sub_service) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, Sub service list not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Sub service list updated successfully',
            "sub_service_id" => $sub_service->sub_service_id
        ], 200);    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {

            $service = SubService::where('sub_service_id', $id)->delete();
        
            return response()->json([
                'success' => 1,
                'message' => 'Sub service list deleted successfully',
            ], 200);
        }
    }
}
