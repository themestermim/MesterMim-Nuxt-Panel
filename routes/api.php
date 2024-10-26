<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {

});

Route::group(['middleware' => ['guest:api']], function () {

});

Route::get('/', function () {
    return response()->json(['msg' => "hi"]);
});
