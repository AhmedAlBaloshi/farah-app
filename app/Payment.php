<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
