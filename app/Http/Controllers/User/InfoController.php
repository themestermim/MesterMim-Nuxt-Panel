<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info() {
        if(auth()->check()) {
            return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user()));
        }

        return ResponseHelper::unAuthorize();
    }
}
