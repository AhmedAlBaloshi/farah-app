<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $query = Order::join('order_product', 'order_product.order_id', '=', 'orders.id');
        if ($request->list != 'all') {
            $query = $query->where('orders.delivery_status', '!=', 'Delivered')
                ->where('orders.delivery_status', '!=', 'Cancelled');
        }
        $orders = $query->groupBy('orders.id')
            ->get();
        return view('orders.list', compact('orders'));
    }
    public function updateStatus(Request $request, $id)
    {
        if ((int)$id > 0) {
            $order = Order::findOrFail($id);
            $order->delivery_status = $request->status;
            $order->update();

            return Response::json([
                "code" => 200,
                "response_status" => "success",
                "message"         => "Record updated successfully",
                "data"            => []
            ]);
        }

        return Response::json([
            "code" => 500,
            "response_status" => "error",
            "message"         => "Something went wrong"
        ]);
    }
    public function view($id)
    {
        $order = Order::select(DB::raw('orders.*, CONCAT(G.firstname, " ",G.lastname) as guest_name, CONCAT(U.firstname, " ",U.lastname) as user_name,U.email as user_email,G.email as guest_email,U.mobile_no as user_mobile_no,G.mobile_no as guest_mobile_no'))
            ->leftJoin('users as G', 'G.id', '=', 'orders.guest_id')
            ->leftJoin('users as U', 'U.id', '=', 'orders.user_id')
            ->where('orders.id', $id)
            ->first();

        $orderDetails = Order::select(DB::raw('product.product_id,product.product_name as product_name,product.product_image as product_image,order_product.quantity,order_product.amount as unit_price'))
            ->join('order_product', 'order_product.order_id', '=', 'orders.id')
            ->join('product', 'product.product_id', '=', 'order_product.product_id')
            ->where('orders.id', $id)
            ->get();
        $order['details'] = $orderDetails;
        return view('orders.view', compact('order'));
    }
}
