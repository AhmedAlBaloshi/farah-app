<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Service;
use App\Models\ServiceList;
use Validator;
use APIHelper;
use Hash;
use DB;
use Image;
use File;
use URL;

class ServiceController extends BaseController
{	
    public function get_service(Request $request) {
        
        $result = Service::get();

        $data = [];
        if (count($result) > 0) {
            foreach($result as $key => $val) {
                $data[$key] = $val;
                if (!empty($val->image)) {
                    $filename = URL::to('/service-image/' . $val->image);
                    $data[$key]->image = $filename;
                }
            }
        }

        return $this->send_response($data, '');
    }
    
    public function get_service_list(Request $request) {

        $validator = Validator::make($request->all(),[
            'service_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->send_error($validator->errors()->first());  
        }
        
        $result = ServiceList::where('service_id','=',$request->service_id)->get();

        $data = [];
        if (count($result) > 0) {
            foreach($result as $key => $val) {
                $data[$key] = $val;
                if (!empty($val->image)) {
                    $filename = URL::to('/service-image/' . $val->image);
                    $data[$key]->image = $filename;
                }
            }
        }

        return $this->send_response($data, '');
    }
}
    