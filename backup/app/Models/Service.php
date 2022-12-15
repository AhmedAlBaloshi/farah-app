<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $connection = 'farah';
    protected $table      = 'service';
    protected $primaryKey = 'service_id';
    public $timestamps    = true;
    
    protected $fillable = [
        'service_name',
        'service_name_ar',
        'image',
        'created_at',
        'updated_at'
    ];

    public static function add($params=[])
    {
        if(!empty($params)) {
            
            $image = "";
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid().'-'.$file->getClientOriginalName();
                
                //Move Uploaded File
                $destinationPath = 'service-image';
                $file->move($destinationPath,$fileName);
                $image = $fileName;
            }

            return self::create([
                'service_name'     => $params['service_name'],
                'service_name_ar'  => $params['service_name_ar'],
                'image'            => $image
            ]);
        }
    }

    public static function updateRecords($id, $params=[])
    {
        if(!empty($params) && (int)$id > 0) {
            
            $image = "";
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid().'-'.$file->getClientOriginalName();
                
                //Move Uploaded File
                $destinationPath = 'service-image';
                $file->move($destinationPath,$fileName);
                $image = $fileName;
            }

            $service = Service::where('service_id',$id)->first();
            if($service) {
                $service->service_name     = $params['service_name'];
                $service->service_name_ar  = $params['service_name_ar'];
                if(!empty($image)) {
                    $service->image = $image;
                }
                $service->save();
            }
        }
    }
    
}