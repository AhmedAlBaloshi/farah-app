<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function detail(){
        return $this->hasMany(OrderDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function guest(){
        return $this->belongsTo(User::class,'guest_id');
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
