<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceList;
use App\Models\SubService;
use Response;

class GeneralController extends Controller
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

    public function getServiceList(Request $request)
    {
        $serviceId   = $request->get('service_id');
        $serviceList = [];
        if ((int)$serviceId > 0) {
            $serviceList = ServiceList::where('service_id',$serviceId)->pluck('service_name','service_list_id')->toArray();
        }
        return $serviceList;
    }

    public function getSubServiceList(Request $request)
    {
        $serviceListId   = $request->get('service_list_id');
        $subServiceList = [];
        if ((int)$serviceListId > 0) {
            $subServiceList = SubService::where('service_list_id',$serviceListId)->pluck('sub_service_name','sub_service_id')->toArray();
        }
        return $subServiceList;
    }

    
    
}