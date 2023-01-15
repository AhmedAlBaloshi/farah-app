<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Models\Product;
use App\Models\SubService;

/*use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;*/

class BaseController extends Controller
{

    public function search(Request $request)
    {
        $Products = Product::select('product_id', 'product_name', 'product_name_ar','product_image','rating','rate')->where('product_name', 'like', '%' . $request->search . '%')->get();
        $subServices = SubService::select('sub_service_id', 'sub_service_name', 'sub_service_name_ar')->where('sub_service_name', 'like', '%' . $request->search . '%')->get();

        return response()->json([
            'success' => 1,
            'data' => $Products->merge($subServices)
        ], 200);
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_response($result, $message)
    {
        $response = [
            'success' => 200,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_error($error, $data = [], $code = 200)
    {
        if (empty($data)) {
            $data = (object)[];
        }
        $response = [
            'success' => 400,
            'data'    => $data,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}
