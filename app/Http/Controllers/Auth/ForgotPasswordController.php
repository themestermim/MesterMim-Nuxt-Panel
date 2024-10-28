<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return ResponseHelper::formatResponse(true, 200, trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return ResponseHelper::formatResponse(false, 422, trans($response));
    }
}
