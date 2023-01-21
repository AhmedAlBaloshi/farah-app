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
        'detail',
        'created_at',
        'updated_at',
        'service_list_id'
    ];

    public function banner()
    {
        return $this->hasOne("App\Models\Banner", 'sub_service_id');
    }

    public function serviceList()
    {
        return $this->belongsTo("App\Models\ServiceList", "service_list_id");
    }

    public function availabilities()
    {
        return $this->hasMany("App\Models\ProductAvailability", "sub_service_id", "sub_service_id");
    }

    public function images()
    {
        return $this->hasMany("App\Models\ProductImage", "sub_service_id", "sub_service_id");
    }

    public function rating()
    {
        return $this->hasMany("App\Models\ProductRating", "sub_service_id", "sub_service_id");
    }

    public function product()
    {
        return $this->hasOne("App\Models\Product", "sub_service_id");
    }

    public static function add($params = [])
    {
        if (!empty($params)) {

            $sub_service = self::create([
                'sub_service_name'    => $params['sub_service_name'],
                'sub_service_name_ar' => $params['sub_service_name_ar'],
                'service_list_id'     => $params['service_list_id'],
                'detail'     => $params['detail'],
            ]);

            // add records in product availability
            if (!empty($params['available'])) {
                $availabilityParams = [];
                foreach ($params['available'] as $key => $item) {
                    $availabilityParams[] = [
                        'sub_service_id' => $sub_service->sub_service_id,
                        'date'       => $item['date'],
                        'time'       => $item['time'],
                        'is_active'  => !empty($item['is_active']) ? 1 : 0,
                        'start_time' =>  $item['start_time'],
                        'end_time' =>  $item['end_time']
                    ];
                }
                ProductAvailability::add($availabilityParams);
            }
            if (!empty($params['sub_service_image'])) {

                $subServiceImageParams = [];
                foreach ($params['sub_service_image'] as $key => $item) {
                    $subServiceImageParams[] = [
                        'sub_service_id' => $sub_service->sub_service_id,
                        'image'       => $item['image'],
                    ];
                }
                ProductImage::add($subServiceImageParams);
            }
            return $sub_service;
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {

            $sub_service = SubService::where('sub_service_id', $id)->first();
            if ($sub_service) {
                $sub_service->sub_service_name     = $params['sub_service_name'];
                $sub_service->sub_service_name_ar  = $params['sub_service_name_ar'];
                $sub_service->service_list_id       = $params['service_list_id'];
                $sub_service->detail       = $params['detail'];
                $sub_service->save();

                // add records in product availability
                if (!empty($params['available'])) {
                    $availabilityParams = [];
                    foreach ($params['available'] as $key => $item) {
                        $availabilityParams[] = [
                            'date'       => $item['date'],
                            'time'       => $item['time'],
                            'is_active'  => !empty($item['is_active']) ? 1 : 0,
                            'start_time' =>  $item['start_time'],
                            'end_time' =>  $item['end_time']
                        ];
                    }

                    ProductAvailability::updateRecords($availabilityParams, $sub_service->sub_service_id);
                }
                if (!empty($params['sub_service_image'])) {

                    $subServiceImageParams = [];
                    foreach ($params['sub_service_image'] as $key => $item) {
                        $subServiceImageParams[] = [
                            'image'       => $item['image'],
                        ];
                    }
                    ProductImage::updateRecords($subServiceImageParams, $sub_service->sub_service_id);
                }
                return $sub_service;
            }
        }
    }
}
