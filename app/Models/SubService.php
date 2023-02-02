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
        'minutes',
        'start_time',
        'end_time',
        'is_active',
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

    public function timeSlots()
    {
        return $this->hasMany("App\Models\ProductTimeSlot", "sub_service_id", "sub_service_id");
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
                'minutes'     => $params['minutes'],
                'start_time'     => $params['start_time'],
                'end_time'     => $params['end_time'],
                'is_active'     => !empty($params['is_active']) ? 1 : 0,
            ]);

            // Product Create
            Product::create([
                'sub_service_id' => $sub_service->sub_service_id,
                'address' => $params['address'],
                'address_ar' => $params['address_ar'],
                'rate' => $params['amount'],
            ]);

            // Add record in time slot
            $params['time_slot'] = [
                'minutes' => $params['minutes'],
                'sub_service_id' => $sub_service->sub_service_id,
                'start_time' => $params['start_time'],
                'end_time' => $params['end_time']
            ];
            ProductTimeSlot::add($params['time_slot']);
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
                $sub_service->minutes     = $params['minutes'];
                $sub_service->start_time     = $params['start_time'];
                $sub_service->end_time     = $params['end_time'];
                $sub_service->is_active       = !empty($params['is_active']) ? 1 : 0;
                $sub_service->save();

                // Product Create
                $product = Product::findOrFail($params['product_id']);
                $product->sub_service_id = $sub_service->sub_service_id;
                $product->address = $params['address'];
                $product->address_ar = $params['address_ar'];
                $product->rate = $params['amount'];
                $product->update();

                // // add records in product availability
                // if (!empty($params['available'])) {
                //     $availabilityParams = [];
                //     foreach ($params['available'] as $key => $item) {
                //         $availabilityParams[] = [
                //             'date'       => $item['date'],
                //             'time'       => $item['time'],
                //             'is_active'  => !empty($item['is_active']) ? 1 : 0,
                //             'start_time' =>  $item['start_time'],
                //             'end_time' =>  $item['end_time']
                //         ];
                //     }

                //     ProductAvailability::updateRecords($availabilityParams, $sub_service->sub_service_id);
                // }
                // add records in time slot

                if (!empty($params['time_slot'])) {
                    $params['time_slot'] = [
                        'minutes' => $params['minutes'],
                        'sub_service_id' => $sub_service->sub_service_id,
                        'start_time' => $params['start_time'],
                        'end_time' => $params['end_time']
                    ];
                    // dd($params['time_slot']);
                    ProductTimeSlot::updateRecords($params['time_slot'], $sub_service->sub_service_id);
                }

                if (!empty($params['sub_service_image'])) {
                    ProductImage::updateRecords($params['sub_service_image'], $sub_service->sub_service_id);
                }
                return $sub_service;
            }
        }
    }
}
