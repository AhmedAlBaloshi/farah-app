<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Models\Product;
use App\Models\SubService;
use Illuminate\Support\Facades\DB;

/*use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;*/

class BaseController extends Controller
{

    public function search(Request $request)
    {
        $Products = Product::where('product_name', 'like', '%' . $request->search . '%')->get();
        $subServices = SubService::select(DB::raw('sub_service.*, product.product_image,  product.discount,product.address, product.address_ar, product.rate, AVG(product_rating.rating) as rating'))
            ->with('banner')
            ->leftJoin('product', 'product.sub_service_id', '=', 'sub_service.sub_service_id')
            ->leftJoin('product_rating', 'product_rating.sub_service_id', '=', 'sub_service.sub_service_id')
            ->where('sub_service_name', 'like', '%' . $request->search . '%')
            ->groupBy('sub_service.sub_service_id')->get();

        return response()->json([
            'success' => 1,
            'data' => [
                'products' => $Products,
                'sub_services' => $subServices
            ]
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
