<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    ///////////////////////////////////////////////////////////////////

    public function sendResponse($message, $data = null)
    {
        $response = [
            "code" => 200,
            "status" => "Success",
            "message" => $message,
        ];
        if($data) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }
    
    ///////////////////////////////////////////////////////////////////

    public function sendError($message)
    {
        $response = [
            "code" => 400,
            "status" => "failed",
            "message" => $message,
        ];
        return response()->json($response);
    }
}
