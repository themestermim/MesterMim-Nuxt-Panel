<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info(Request $request) {

        $headers = $request->headers->all();

        if($request->header("lang") == "fa"){
            return response()->json([
                'headers' => $headers
            ]);
        }

        if (!$request->hasHeader('Authorization')) {
            return ResponseHelper::formatResponse(false, 400, ["msg" => "Token not provided"]);
        }

        if (User::count() == 0) {
            return ResponseHelper::formatResponse(false, 404, ["msg" => "User not found"]);
        }

        if(auth()->check() && auth()->user() && User::count() > 0) {
            return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user()));
        }

        return ResponseHelper::unAuthorize();
//        return ResponseHelper::unAuthorize();
    }
}
