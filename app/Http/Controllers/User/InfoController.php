<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserDescriptions;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info(Request $request) {

        if (!$request->hasHeader('Authorization')) {
            return ResponseHelper::unAuthorize();
        }

//        auth()->check() &&
        switch ($request->header('lang')) {
            case 'fa':
            case 'en':
                    if(auth()->user() && User::count() > 0) {
                        $descriptions = UserDescriptions::where('user_id', auth()->id())->get();

                        return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user(), $descriptions));
                    }
                    return ResponseHelper::unAuthorize();
                break;

            default:
                return ResponseHelper::langUnsupport();
        }
    }
}
