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
        return $this->belongsTo(SubService::class, 'service_id');
    }

    public static function add($params = [])
    {
        if (!empty($params)) {
            if ($params['product_id']) {
                $product = Product::findOrFail($params['product_id']);
                $discount = ($params['percentage'] / 100) * $product->rate;
                $product->discount = $discount;
                $product->update();
            }
            if ($params['service_id']) {
                $product = Product::latest()->where('sub_service_id', $params['service_id'])->first();
                if ($product) {
                    $discount = ($params['percentage'] / 100) * $product->rate;
                    $product->discount = $discount;
                    $product->update();
                }
            }
            $params['check'] == 'is_product' ? $params['service_id'] = null : $params['product_id'] = null;
            return self::create($params);
        }
    }

    public static function updateRecords($id, $params = [])
    {
        if (!empty($params) && (int)$id > 0) {
            $offer = Offer::where('id', $id)->first();
            if ($offer) {
                $params['check'] == 'is_product' ? $params['service_id'] = null : $params['product_id'] = null;
                $offer->update($params);
                if ($params['product_id']) {
                    $product = Product::findOrFail($params['product_id']);
                    $discount = ($params['percentage'] / 100) * $product->rate;
                    $product->discount = $discount;
                    $product->update();
                }
                if ($params['service_id']) {
                    $product = Product::latest()->where('sub_service_id', $params['service_id'])->first();
                    if ($product) {
                        $discount = ($params['percentage'] / 100) * $product->rate;
                        $product->discount = $discount;
                        $product->update();
                    }
                }
                return $offer;
            }
        }
    }
}
