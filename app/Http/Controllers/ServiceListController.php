<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceList;
use Response;

class ServiceListController extends Controller
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
        $serviceList = ServiceList::with('service')->get();
        return view('service-list.list',compact('serviceList'));
    }

    /**
     * Create Service
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service = \App\Models\Service::pluck('service_name','service_id')->toArray();
        return view('service-list.form',compact('service'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'image'              => 'required',
            'service_id'         => 'required'
        ]);

        ServiceList::add($request->all());

        return redirect()->route('service_list_index')->with('success','Service List created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceList  $serviceList
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = \App\Models\Service::pluck('service_name','service_id')->toArray();
        $serviceList = [];
        if ((int)$id > 0) {
            $serviceList  = ServiceList::find($id);
        }
        
        return view('service-list.form',compact(['service','serviceList']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'service_id'         => 'required'
        ]);

        ServiceList::updateRecords($id,$request->all());

        return redirect()->route('service_list_index')->with('success','Service List updated successfully.');
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
            
            $service = ServiceList::where('service_list_id',$id)->delete();
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
