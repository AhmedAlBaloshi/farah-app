<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class ProductTimeSlot extends Model
{
    protected $table      = 'product_time_slot';
    protected $primaryKey = 'id';
    public $timestamps    = true;

    protected $fillable = [
        // 'product_availability_id',
        'sub_service_id',
        'start_time',
        'end_time',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['product_availability_id'];

    public function productAvailability()
    {
        return $this->belongsTo("App\Models\ProductAvailability", "product_availability_id", "product_availability_id");
    }

    public function subService()
    {
        return $this->belongsTo("App\Models\SubService", "sub_service_id", "sub_service_id");
    }

    public static function add($param = [])
    {
        if ($param) {
            foreach (self::getTimeSlot($param['minutes'], $param['start_time'], $param['end_time']) as $ts) {
                self::create([
                    'sub_service_id' => $param['sub_service_id'],
                    'start_time' => $ts['start_time'],
                    'end_time' => $ts['end_time'],
                ]);
            }
        }
    }

    public static function updateRecords($params = [], $sub_service_id)
    {
        if (!empty($params)) {
            self::where('sub_service_id', $sub_service_id)
                ->delete();

            foreach (self::getTimeSlot($params['minutes'], $params['start_time'], $params['end_time']) as $ts) {
                self::create([
                    'sub_service_id' => $sub_service_id,
                    'start_time'       => $ts['start_time'],
                    'end_time'       => $ts['end_time'],
                ]);
            }
        }
    }

    public static function getTimeSlot($interval, $start_time, $end_time)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $i = 0;
        $time = [];
        while (strtotime($startTime) <= strtotime($endTime)) {
            $start = $startTime;
            $end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $i++;
            if (strtotime($startTime) <= strtotime($endTime)) {
                $time[$i]['start_time'] = $start;
                $time[$i]['end_time'] = $end;
            }
        }
        return $time;
    }
}
