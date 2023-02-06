<?php

namespace App\Models;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
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

    public static function getTimeSlot($int, $start_time, $end_time)
    {
        $start = Carbon::create($start_time);
        $end = Carbon::create($end_time != "00:00:00" ? $end_time : '24:00:00');

        $interval = DateInterval::createFromDateString($int . ' minutes');
        $period = new DatePeriod($start, $interval, $end);

        $timeslots = [];
        foreach ($period as $key => $dt) {
            $start_time = $dt->format("H:i");
            $end_time = $dt->addMinutes($int)->format("H:i");
            $timeslots[] = [
                'start_time' => $start_time,
                'end_time' => $end_time
            ];
        }
        return $timeslots;
    }
}
