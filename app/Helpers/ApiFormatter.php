<?php

namespace App\Helpers;


class ApiFormatter
{
    protected static $response = [
        'statusCode' => null,
        'success' => null,
        'message' => null,
        'data' => null
    ];

    public static function format($statusCode = null, $success = null, $message = null, $data = null)
    {
        self::$response['statusCode'] = $statusCode;
        self::$response['success'] = $success;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['statusCode']);
    }
}
