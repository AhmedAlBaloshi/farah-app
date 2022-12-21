<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function detail(){
        return $this->hasMany(OrderDetail::class);
    }

    public function product(){
        return $this->hasMany(OrderProduct::class);
    }
    
    public function timeSlot(){
        return $this->hasMany(OrderBookTimeSlot::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

}
