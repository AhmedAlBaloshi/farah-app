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
        'product_availability_id',
        'start_time',
        'end_time',
        'created_at',
        'updated_at'
    ];

    public function productAvailability()
    {
        return $this->belongsTo("App\Models\ProductAvailability", "product_availability_id", "product_availability_id");
    }

    public static function add($param = [])
    {
        if ($param) {
            foreach (self::getTimeSlot(30, $param['start_time'], $param['end_time']) as $ts) {
                self::create([
                    'product_availability_id' => $param['product_availability_id'],
                    'start_time' => $ts['start_time'],
                    'end_time' => $ts['end_time'],
                ]);
            }
        }
    }

    public static function updateRecords($params = [], $oldAvailId, $newAvailId)
    {
        if (!empty($params)) {
            self::where('product_availability_id', $oldAvailId)
                ->delete();
            foreach ($params as $key => $param) {
                self::create([
                    'product_availability_id' => $newAvailId,
                    'start_time'       => $param['start_time'],
                    'end_time'       => $param['end_time'],
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
