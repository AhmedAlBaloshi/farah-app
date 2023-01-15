<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{

    protected $table      = 'product_rating';
    protected $primaryKey = 'product_rating_id';
    public $timestamps    = true;

    protected $fillable = [
        'user_id',
        'sub_service_id',
        'product_id',
        'rating',
        'created_at',
        'updated'
    ];

    public function subService()
    {
        return $this->belongsTo("App\Models\sub_service", "sub_service_id", "sub_service_id");
    }

    public static function add($param = [])
    {
        if ($param) {
                return self::create([
                    'sub_service_id' => isset($param['sub_service_id'])?$param['sub_service_id']:null,
                    'product_id' => isset($param['product_id'])?$param['product_id']:null,
                    'rating'       => $param['rating'],
                    'user_id'       => auth()->user()->id,
                ]);
        }
    }
}
