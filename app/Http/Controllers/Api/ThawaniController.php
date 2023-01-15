<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Payment;
use Illuminate\Support\Facades\Session;

class ThawaniController extends Controller
{
    public function checkout(Request $request, $order_id)
    {
        $item = [];
        foreach ($request->product as $prod) {
            array_push(
                $item,
                array(
                    'name' => $prod['name'],
                    'quantity' => $prod['quantity'],
                    'unit_amount' => $prod['unit_price'] * 1000
                    // "name" => $details['serviceName'] . "-" . $details['name'],
                    // "quantity" => $details['quantity'],
                    // "unit_amount" => ($details['price'] * 1000)
                )
            );
        }

        $data['products'] = $item;
        $data['customer_id'] = '';
        // $data['success_url'] = 'https://laundry.thiqatech.com/laravel_admin/public/thawani/success/' . $userid . '/' . $total . '/' . $booking->id;
        // $data['cancel_url'] = 'https://laundry.thiqatech.com/laravel_admin/public/thawani/cancel/' . $booking->id;
        $data['success_url'] = url('api/thawani-pay/success', $order_id);
        $data['cancel_url'] = url('api/thawani-pay/cancel', $order_id);
        $data['save_card_on_success'] = false;

        $curl = curl_init('https://uatcheckout.thawani.om/api/v1/checkout/session');
        // live $curl = curl_init('https://checkout.thawani.om/api/v1/checkout/session');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive',
            'thawani-api-key: rRQ26GcsZzoEhbrP2HZvLYDbn9C9et'
            // live 'thawani-api-key: eqI51NlS5nu18Azz7yvxDs1FNup0sd'
        ));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(($data)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response);
        //   dd($json);
        Session::put('session_id', $json->data->session_id);
        //dd(json_encode($json));
        // live    return redirect()->to('https://checkout.thawani.om/pay/' . $json->data->session_id . '?key=xCdwGLpYftflM0jAREOb5izaq4UWeG ');
        if ($json->success)
            return redirect()->to('https://uatcheckout.thawani.om/pay/' . $json->data->session_id . '?key=HGvTMLDssJghr9tlN9gr4DVYt0qyBy ');
        else
            return redirect()->to('https://perfumes.fasttech.om/perfume/public/api/payment_failed');
    }
    public function success($order_id)
    {
        $curl = curl_init();
        $session_id = Session::get('session_id');
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://uatcheckout.thawani.om/api/v1/checkout/session/" . $session_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "thawani-api-key: rRQ26GcsZzoEhbrP2HZvLYDbn9C9et"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $response = json_decode($response)->data;
            foreach ($response as $res) {
                $payment = new Payment();
                $payment->order_id = $order_id;
                $payment->payment_method = 'Thawani';
                $payment->amount = $res->total_amount;
                $payment->payment_date = date('Y-m-d', strtotime($res->created_at));
                $payment->payment_status = 'paid';
                $payment->timestamps = false;
                $payment->save();
                if ($payment) {
                    return response()->json([
                        'success' => 1,
                        'message' => 'Order #' . $order_id . ' has been successfully paid.',
                        "payment_id" => $payment->id
                    ], 200);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Failed to add payment for order #' . $order_id
                    ], 404);
                }
            }
        }
    }
    public function cancel($order_id)
    {
        return response()->json([
            'success' => 0,
            'message' => 'Order #' . $order_id . ' payment canceled.'
        ], 200);
    }
}
