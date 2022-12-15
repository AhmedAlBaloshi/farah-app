<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
/*use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;*/

class BaseController extends Controller
{
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
