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
        $Products = Product::select(DB::raw('product.product_id as id,product.product_name as name,product.product_name_ar as name_ar,product.product_image as image,product.rate as price,product.discount,AVG(product_rating.rating) as rating'))
            ->leftJoin('product_rating', 'product_rating.product_id', '=', 'product.product_id')
            ->where('product_name', 'like', '%' . $request->search . '%')
            ->groupBy('product.product_id')
            ->get();
        $subServices = SubService::select(DB::raw('sub_service.sub_service_id as id, sub_service.sub_service_name as name,sub_service.sub_service_name_ar as name_ar,product.product_image as image, product.discount, product.rate as price, product.discount,AVG(product_rating.rating) as rating'))
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
