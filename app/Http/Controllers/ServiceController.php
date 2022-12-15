<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Response;

class ServiceController extends Controller
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
        $service = Service::get();
        return view('service.list',compact('service'));
    }

    /**
     * Create Service
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('service.form');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
            'image'              => 'required'
        ]);

        Service::add($request->all());

        return redirect()->route('service_index')->with('success','Service created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Veterinarian  $veterinarian
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $service = [];
        if ((int)$id > 0) {
            $service  = Service::find($id);
        }
        
        return view('service.form',compact('service'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'service_name'       => 'required',
            'service_name_ar'    => 'required',
        ]);

        Service::updateRecords($id,$request->all());

        return redirect()->route('service_index')->with('success','Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Veterinarian  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ((int)$id > 0) {
            
            $service = Service::where('service_id',$id)->delete();
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
