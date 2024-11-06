<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function formatResponse($success, $status, $data, $pagination = null)
    {
        $response = [
            'success' => $success,
            'status' => $status,
            'data' => $data,
        ];

        if ($pagination) {
            $response['pagination'] = $pagination;
        }

        return $response;
    }

    public static function unAuthorize()
    {
        $response = [
            'success' => false,
            'status' => 401,
            'data' => [
                'message' => 'Unauthorized.'
            ],
        ];

        return $response;
    }

    public static function langUnsupport()
    {
        $response = [
            'message' => 'Language not supported.'
        ];

        return $response;
    }
}
