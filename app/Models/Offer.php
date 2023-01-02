<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $fillable = [
        'service_id',
        'product_id',
        'title',
        'percentage',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            return self::create($params);
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {
            $offer = Offer::where('id', $id)->first();
            if ($offer) {
                $offer->update($params);
                return $offer;
            }
        }
    }
}
