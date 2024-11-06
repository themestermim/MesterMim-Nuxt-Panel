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

        $headers = $request->headers->all();

        if (!$request->hasHeader('Authorization')) {
            return ResponseHelper::formatResponse(false, 400, ["msg" => "Token not provided"]);
        }

        switch ($request->header('lang')) {
            case 'fa':
            case 'en':
                    if(auth()->check() && auth()->user() && User::count() > 0) {
                        $description = UserDescriptions::where('user_id', auth()->id())
                            ->where('lang', $request->header('lang'))
                            ->first();

                        return ResponseHelper::formatResponse(true, 200, new UserResource(auth()->user(), $description));
                    }
                    return ResponseHelper::unAuthorize();
                break;

            default:
                return ResponseHelper::langUnsupport();
        }
    }
}
