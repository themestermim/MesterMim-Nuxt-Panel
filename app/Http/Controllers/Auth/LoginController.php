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
//        $token = (string)$this->guard()->getToken();
        $exp = $this->guard()->getPayload()->get('exp');

        $response = ResponseHelper::formatResponse(
            true,
            200,
            [
                'token'      => $this->token,
                'exp_date'   => $exp,
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
            $this->username() => 'User not found.',
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

}
