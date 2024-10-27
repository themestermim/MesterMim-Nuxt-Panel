<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Helpers\ResponseHelper;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user) {
        // check if url is valid
        if(! URL::hasValidSignature($request)) {
            $response = ResponseHelper::formatResponse(
                false,
                422,
                [
                    "message" => "Invalid url",
                ]
            );
            return new JsonResponse($response, 422);
        }

        // check if email is verified
        if($user->hasVerifiedEmail()) {
            $response = ResponseHelper::formatResponse(
                false,
                422,
                [
                    "message" => "Email has already verified",
                ]
            );
            return new JsonResponse($response, 422);
        }

        // verify email
        $user->markEmailAsVerified();
        event(new Verified($user));
        $response = ResponseHelper::formatResponse(
            true,
            200,
            [
                "message" => "Email verified successfully",
            ]
        );
        return new JsonResponse($response, 200);
    }

    public function resend(Request $request) {
        $this->validate($request, [
            'email' =>'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $response = ResponseHelper::formatResponse(
                false,
                422,
                [
                    'message' => 'User not found',
                ]
            );

            return new JsonResponse($response, 422);
        }

        // check if email is verified
        if($user->hasVerifiedEmail()) {
            $response = ResponseHelper::formatResponse(
                false,
                422,
                [
                    "message" => "Email has already verified",
                ]
            );
            return new JsonResponse($response, 422);
        }

        $user->sendEmailVerificationNotification();

        $response = ResponseHelper::formatResponse(
            true,
            200,
            [
                "message" => "Email sent successfully",
            ]
        );
        return new JsonResponse($response, 200);
    }
}
