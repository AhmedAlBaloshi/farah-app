<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAvailability extends Model
{
    protected $connection = 'farah';
    protected $table      = 'product_availability';
    protected $primaryKey = 'id';
    public $timestamps    = true;
    
    protected $fillable = [
        'product_id',
        'date',
        'time',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo("App\Models\Product", "product_id", "product_id");
    }

    public static function add($params=[])
    {
        if(!empty($params)) {
            foreach ($params as $key => $param) {
                self::create([
                    'product_id' => $param['product_id'],
                    'date'       => $param['date'],
                    'time'       => $param['time'],
                    'is_active'  => !empty($param['is_active']) ? 1 : 0
                ]);
            }
        }
    }

    public static function updateRecords($params=[])
    {
        if(!empty($params)) {
            foreach ($params as $key => $param) {
                if($param['id'] > 0) {
                    self::where(['product_id'=>$param['product_id'],'id'=>$param['id']])
                    ->update([
                        'date'       => $param['date'],
                        'time'       => $param['time'],
                        'is_active'  => !empty($param['is_active']) ? 1 : 0,
                    ]);
                } else {
                    self::create([
                        'product_id' => $param['product_id'],
                        'date'       => $param['date'],
                        'time'       => $param['time'],
                        'is_active'  => !empty($param['is_active']) ? 1 : 0
                    ]);
                }
            }
        }
    }
    
}