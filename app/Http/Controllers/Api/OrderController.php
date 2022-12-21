<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderBookTimeSlot;
use App\OrderDetail;
use App\OrderProduct;
use App\Payment;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        if ($orders) {
            return response()->json([
                'success' => 1,
                'orders' => $orders
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load orders from database'
        ], 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'payment_type' => 'required',
        ]);

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->guest_id = $request->guest_id;
        $order->shipping_address = $request->shipping_address;
        $order->payment_type = $request->payment_type;
        $order->manual_payment = $request->manual_payment ? $request->manual_payment : 0;
        $order->grand_total = $request->price;
        $order->date = $request->date;
        $order->save();

        $detail = new OrderDetail();
        $detail->order_id = $order->id;
        $detail->product_id = $request->product_id;
        $detail->price = $request->price;
        $detail->quantity = $request->quantity;
        $detail->payment_status = $request->payment_status;
        $detail->booking_date = $request->date;
        $detail->booking_start_time = $request->start_time;
        $detail->booking_end_time = $request->end_time;
        $detail->save();

        if ($request->product_id) {
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $request->product_id;
            $orderProduct->order_id = $order->id;
            $orderProduct->quantity = $request->quantity;
            $orderProduct->amount = $request->price;
            $orderProduct->timestamps = false;

            $orderProduct->save();
        } else {
            $bookTimeSlot = new OrderBookTimeSlot();
            $bookTimeSlot->order_id = $order->id;
            $bookTimeSlot->book_date = $request->date;
            $bookTimeSlot->book_time = $request->book_time;
            $bookTimeSlot->timestamps = false;
            $bookTimeSlot->save();
        }
        if ($order) {
            return response()->json([
                'success' => 1,
                'message' => 'Order added successfully',
                "order_id" => $order->id
            ], 200);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Failed to add Order'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::select(DB::raw('orders.*, CONCAT(G.firstname, " ",G.lastname) as guest_name, CONCAT(U.firstname, " ",U.lastname) as user_name'))
            ->leftJoin('users as G', 'G.id', '=', 'orders.guest_id')
            ->leftJoin('users as U', 'U.id', '=', 'orders.user_id')
            ->where('orders.id', $id)
            ->first();

        $orderDetails = Order::select(DB::raw('OD.*,CONCAT(S.firstname, " ",S.lastname) as seller_name, P.product_name as product_name_en, P.product_name_ar as product_name_ar , TS.book_time as book_time, TS.book_date as book_date, OP.quantity as product_qty, OP.amount as product_amount'))
            ->join('order_details as OD', 'OD.order_id', '=', 'orders.id')
            ->join('users as U', 'U.id', '=', 'orders.user_id')
            ->leftJoin('product as P', 'P.product_id', '=', 'OD.product_id')
            ->leftJoin('order_book_time_slot as TS', 'TS.order_id', '=', 'orders.id')
            ->leftJoin('order_product as OP', 'OP.order_id', '=', 'orders.id')
            ->leftJoin('users as S', 'S.id', '=', 'OD.seller_id')
            ->where('orders.id', $id)
            ->get();
        $order['details'] = $orderDetails;
        if ($order) {
            return response()->json([
                'success' => 1,
                'order' => $order
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed, Order not found'
        ], 404);
    }

    public function payment(Request $request, $order_id)
    {
        $this->validate($request, [
            'amount' => 'required',
            'payment_date' => 'required',
            'payment_status' => 'required',
        ]);
        $discount = $request->discount ? $request->discount : 0;
        $taxes = $request->taxes ? $request->taxes : 0;

        // update Order payment status

        $order = Order::findOrFail($order_id);
        $order->payment_status = $request->payment_status;
        $order->update();

        //create payment 
        $payment = new Payment();
        $payment->order_id = $order_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $request->amount;
        $payment->discount = $discount;
        $payment->taxes = $taxes;
        $payment->payment_date = $request->payment_date;
        $payment->net_amount = ($request->amount - $discount - $taxes);
        $payment->payment_status = $request->payment_status;
        $payment->timestamps = false;
        $payment->save();
        if ($payment) {
            return response()->json([
                'success' => 1,
                'message' => 'Order #' . $order_id . ' has been successfully paid.',
                "payment_id" => $payment->id
            ], 200);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Failed to add payment for order #' . $order_id
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
