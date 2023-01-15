<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAvailability extends Model
{
    protected $table      = 'product_availability';
    protected $primaryKey = 'product_availability_id';
    public $timestamps    = true;

    protected $fillable = [
        'sub_service_id',
        'date',
        'time',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function subService()
    {
        return $this->belongsTo("App\Models\SubService", "sub_service_id", "sub_service_id");
    }

    public function timeSlots()
    {
        return $this->hasMany("App\Models\ProductTimeSlot", "product_availability_id", "product_availability_id");
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                $productAvailable = new ProductAvailability();
                $productAvailable->sub_service_id = $param['sub_service_id'];
                $productAvailable->date = $param['date'];
                $productAvailable->time = $param['time'];
                $productAvailable->is_active = !empty($param['is_active']) ? 1 : 0;
                $productAvailable->save();
                if (!empty($param['time_slot'])) {
                    $availableTimeSlot = [];
                    foreach ($param['time_slot'] as $key => $slot) {
                        $availableTimeSlot[] = [
                            'product_availability_id' => $productAvailable->product_availability_id,
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                        ];
                    }
                    ProductTimeSlot::add($availableTimeSlot);
                }
            }
        }
    }

    public static function updateRecords($params = [], $sub_service_id)
    {
        $product_available_ids = self::select('product_availability_id')->where('sub_service_id', $sub_service_id)
            ->get()->toArray();
            // dd($product_available_ids);
        if (!empty($params)) {
            self::where('sub_service_id', $sub_service_id)
                ->delete();
            foreach ($params as $key => $param) {
                $productAvailable = self::create([
                    'sub_service_id' => $sub_service_id,
                    'date'       => $param['date'],
                    'time'       => $param['time'],
                    'is_active'  => !empty($param['is_active']) ? 1 : 0
                ]);
                if (!empty($param['time_slot'])) {
                    $availableTimeSlot = [];
                    foreach ($param['time_slot'] as $key => $slot) {
                        $availableTimeSlot[] = [
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                        ];
                    }
                    ProductTimeSlot::updateRecords($availableTimeSlot,$product_available_ids[$key]['product_availability_id'],$productAvailable->product_availability_id);
                }
            }
        }
    }
}
