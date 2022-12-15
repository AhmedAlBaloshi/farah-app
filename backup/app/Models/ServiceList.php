<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
    protected $connection = 'farah';
    protected $table      = 'service_list';
    protected $primaryKey = 'service_list_id';
    public $timestamps    = true;
    
    protected $fillable = [
        'service_name',
        'service_name_ar',
        'image',
        'created_at',
        'updated_at',
        'service_id'
    ];

    public function service()
    {
        return $this->hasOne("App\Models\Service", "service_id", "service_id");
    }

    public static function add($params=[])
    {
        if(!empty($params)) {
            
            $image = "";
            if (!empty($params['image'])) {
                $file = $params['image'];
                $fileName = uniqid().'-'.$file->getClientOriginalName();
                
                //Move Uploaded File
                $destinationPath = 'service-list-image';
                $file->move($destinationPath,$fileName);
                $image = $fileName;
            }

            return self::create([
                'service_name'     => $params['service_name'],
                'service_name_ar'  => $params['service_name_ar'],
                'service_id'       => $params['service_id'],
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
                $destinationPath = 'service-list-image';
                $file->move($destinationPath,$fileName);
                $image = $fileName;
            }

            $serviceList = ServiceList::where('service_list_id',$id)->first();
            if($serviceList) {
                $serviceList->service_name     = $params['service_name'];
                $serviceList->service_name_ar  = $params['service_name_ar'];
                $serviceList->service_id       = $params['service_id'];
                if(!empty($image)) {
                    $serviceList->image = $image;
                }
                $serviceList->save();
            }
        }
    }
    
}