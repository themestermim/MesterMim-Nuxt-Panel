<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\InfoController;
use App\Http\Controllers\User\EditController;


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', [LoginController::class,'logout']);
    Route::get('/profile/info', [InfoController::class,'info']);
    Route::post('/profile/edit', [EditController::class,'edit']);
});

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/register', [RegisterController::class,'register']);
    Route::post('/verification/verify/{user}', [VerificationController::class,'verify'])->name('verification.verify');
    Route::post('/verification/resend', [VerificationController::class,'resend']);
    Route::post('/login', [LoginController::class,'login']);
    Route::post('/password/resend', [ForgotPasswordController::class,'sendResetLinkEmail']);
    Route::post('/password/reset', [ResetPasswordController::class,'reset']);
});
