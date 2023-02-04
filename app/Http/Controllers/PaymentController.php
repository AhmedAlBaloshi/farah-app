<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(){
        $payments = Payment::get();
        return view('payment.list',compact('payments'));
    }
}
