<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;

Route::group(['middleware' => ['auth:api']], function () {

});

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/register', [RegisterController::class,'register']);
    Route::post('/verification/verify/{user}', [VerificationController::class,'verify'])->name('verification.verify');
    Route::post('/verification/resend', [VerificationController::class,'resend']);
    Route::post('/login', [LoginController::class,'login']);
});
