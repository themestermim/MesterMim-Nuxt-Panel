<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $token;

    protected function attemptLogin(Request $request)
    {
        $this->token = $this->guard()->attempt($this->credentials($request));

        if(! $this->token) {
            return false;
        }

        $user = $this->guard()->user();
        if($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return false;
        }

        $this->guard()->setToken($this->token);
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = $this->guard()->getToken();

        $response = ResponseHelper::formatResponse(
            true,
            200,
            [
                'token'      => $this->token,
                'token_type' => 'JWT',
            ]
        );

        return new JsonResponse($response, 200);

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();
        if($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $response = ResponseHelper::formatResponse(
                false,
                422,
                [
                    'message' => 'You need to verify your email',
                ]
            );
            return new JsonResponse($response, 422);
        }

        throw ValidationException::withMessages([
            $this->username() => 'Invalid Credentials',
        ]);
    }

    public function logout(Request $request) {
        // باطل کردن توکن ارسالی از سمت کاربر
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            $response = ResponseHelper::formatResponse(
                true,
                200,
                [
                    'message' => 'Logged out successfully',
                ]
            );
        } catch (\Exception $e) {
            $response = ResponseHelper::formatResponse(
                false,
                500,
                [
                    'message' => 'Failed to logout, please try again',
                ]
            );
        }

        return new JsonResponse($response, 200);
    }
//    public function logout (Request $request) {
//        $this->guard()->logout();
//        $this->guard()->invalidate();
//
//        $response = ResponseHelper::formatResponse(
//            true,
//            200,
//            [
//                'message' => 'Logged out successfully',
//            ]
//        );
//        return new JsonResponse($response, 200);
//    }

}
