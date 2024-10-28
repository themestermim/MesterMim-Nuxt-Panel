<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info() {
        if(auth()->check()) {
            return ResponseHelper::formatResponse(true, 200, auth()->user());
        }

        return ResponseHelper::unAuthorize();
    }
}
