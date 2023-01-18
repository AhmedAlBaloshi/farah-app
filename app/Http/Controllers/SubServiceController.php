<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubService;
use Response;

class SubServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show Service List
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subService = SubService::with('serviceList')->get();
        return view('sub-service.list',compact('subService'));
    }

    /**
     * Create Service
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service = \App\Models\ServiceList::pluck('service_name','service_list_id')->toArray();
        return view('sub-service.form',compact('service'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'sub_service_name'    => 'required',
            'sub_service_name_ar' => 'required',
            'service_list_id'     => 'required'
        ]);

        SubService::add($request->all());

        return redirect()->route('sub_service_index')->with('success','Sub Service List created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceList  $serviceList
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = \App\Models\ServiceList::pluck('service_name','service_list_id')->toArray();
        $subService = [];
        if ((int)$id > 0) {
            $subService  = SubService::find($id);
        }
        
        return view('sub-service.form',compact(['service','subService']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'sub_service_name'       => 'required',
            'sub_service_name_ar'    => 'required',
            'service_list_id'         => 'required'
        ]);

        SubService::updateRecords($id,$request->all());

        return redirect()->route('sub_service_index')->with('success','Service List updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceList  $serviceList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {
            
            $service = SubService::where('service_list_id',$id)->delete();
            return Response::json(["code" => 200,
                "response_status" => "success",
                "message"         => "Record deleted successfully",
                "data"            => []
            ]);

        }
        
        return Response::json(["code" => 500, 
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }
}
