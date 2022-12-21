<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBookTimeSlot extends Model
{
    protected $table = 'order_book_time_slot';

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
