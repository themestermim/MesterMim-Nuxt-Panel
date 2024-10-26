<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::group(['middleware' => ['auth:api']], function () {

});

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/register', [RegisterController::class,'register']);
});
