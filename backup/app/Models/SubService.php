<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    protected $connection = 'farah';
    protected $table      = 'sub_service';
    protected $primaryKey = 'sub_service_id';
    public $timestamps    = true;
    
    protected $fillable = [
        'sub_service_name',
        'sub_service_name_ar',
        'created_at',
        'updated_at',
        'service_list_id'
    ];

    public function service()
    {
        return $this->hasOne("App\Models\ServiceList", "service_list_id", "service_list_id");
    }

    public static function add($params=[])
    {
        if(!empty($params)) {
            
            return self::create([
                'sub_service_name'    => $params['sub_service_name'],
                'sub_service_name_ar' => $params['sub_service_name_ar'],
                'service_list_id'     => $params['service_list_id'],
            ]);
        }
    }

    public static function updateRecords($id, $params=[])
    {
        if(!empty($params) && (int)$id > 0) {
            
            $serviceList = SubService::where('sub_service_id',$id)->first();
            if($serviceList) {
                $serviceList->sub_service_name     = $params['sub_service_name'];
                $serviceList->sub_service_name_ar  = $params['sub_service_name_ar'];
                $serviceList->service_list_id       = $params['service_list_id'];
                $serviceList->save();
            }
        }
    }
    
}