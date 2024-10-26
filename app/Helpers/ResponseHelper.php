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
}
