<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
