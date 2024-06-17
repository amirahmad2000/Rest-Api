<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/payment/verify', function (Request $request) {
    $response = Http::post('http://localhost:8000/api/payment/verify' , [
        'token' => $request->token
    ]);

    dd($response);
});
