<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function bookings()
    {
        $bookings = Order::select('orders.*', 'order_details.booking_date', 'order_details.booking_start_time', 'order_details.booking_end_time')->latest()
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('order_book_time_slot', 'order_book_time_slot.order_id', '=', 'orders.id')
            ->groupBy('orders.id')->get();
        return view('booking.list', compact('bookings'));
    }

    public function view($id)
    {
        $booking = Order::select(DB::raw('orders.*, CONCAT(G.firstname, " ",G.lastname) as guest_name, CONCAT(U.firstname, " ",U.lastname) as user_name,U.email as user_email,G.email as guest_email,U.mobile_no as user_mobile_no,G.mobile_no as guest_mobile_no'))
            ->leftJoin('users as G', 'G.id', '=', 'orders.guest_id')
            ->leftJoin('users as U', 'U.id', '=', 'orders.user_id')
            ->where('orders.id', $id)
            ->first();

        $bookingDetails = Order::select('OD.service_id', 'SS.sub_service_name as service_name','P.product_image','OD.price','OD.booking_date','OD.booking_start_time','OD.booking_end_time')
            ->join('order_details as OD', 'OD.order_id', 'orders.id')
            ->join('sub_service as SS', 'SS.sub_service_id', 'OD.service_id')
            ->join('Product as P', 'P.sub_service_id', 'SS.sub_service_id')
            ->where('orders.id', $id)
            ->first();

        $booking['details'] = $bookingDetails;
        // echo json_encode($booking);
        // exit;
        return view('booking.view', compact('booking'));
    }
}
