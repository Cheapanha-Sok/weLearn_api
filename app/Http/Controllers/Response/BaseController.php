<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\\Http\\Response
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'statusCode' => $code,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
    /**
     * return message response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendMessage($message, $code = 200)
    {
        $response = [
            'statusCode' => $code,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */


    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'statusCode' => 500
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

}