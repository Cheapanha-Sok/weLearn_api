<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    public function sendSuccess($data, $message = '', $status = 200)
    {
        $res = [
            'statusCode' => $status,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($res);
    }

    public function sendError($message, $error = [], $status = 422)
    {
        $res = [
            'statusCode' => $status,
            'message' => $message,
            'data' => $error
        ];
        return response()->json($res);
    }

}