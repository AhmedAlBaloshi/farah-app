<?php

namespace App\Models;

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

    public static function add($params = [])
    {
        if ($params) {
            foreach ($params as $key => $param) {
                self::create([
                    'product_availability_id' => $param['product_availability_id'],
                    'start_time'       => $param['start_time'],
                    'end_time'       => $param['end_time'],
                ]);
            }
        }
    }

    public static function updateRecords($params = [], $oldAvailId,$newAvailId)
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
}
