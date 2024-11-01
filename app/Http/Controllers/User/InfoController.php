<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info() {
        if (User::count() == 0) {
            return ResponseHelper::formatResponse(false, 404, ["msg" => "User not found"]);
        }

        if(auth()->check() && auth()->user() && User::count() > 0) {
            return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user()));
        }

        return ResponseHelper::formatResponse(false, 401, ["msg" => "Unauthorized"]);
//        return ResponseHelper::unAuthorize();
    }
}
