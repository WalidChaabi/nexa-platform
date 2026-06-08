<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header('Access-Control-Allow-Origin: 194.204.226.184');
header('Access-Control-Allow-Origin: 194.204.226.185');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
ini_set('allow_url_fopen', 'On');

Route::group(['domain' => 'app.sherin.ma'], function () {

    Route::get('index',function(){
        return view('cmi.paymentRequest');
    });

    Route::post('process',function(Request $request){
        return view('cmi.sendData');
    });

    Route::post('Ok-Fail',function(Request $request){
        return view('cmi.ok-fail');
    });

    Route::post('callback',function(Request $request){
        return view('cmi.callback');
    });
   
});
