<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('temp',array('uses' => 'Seller\ExcelController@cropImages'));
Route::post('post-temp',array('uses' => 'Auth\AuthController@postTemp'));
/* Web Application Routes */
/**************************** Common To All Users ************************************/
Route::post('authenticate',array('uses' => 'Auth\AuthController@authenticate'));
Route::get('/',array('uses' => 'Auth\AuthController@viewLogin'));
Route::get('home',array('uses' => 'Seller\SellerController@home'));
Route::get('dashboard',array('uses' => 'Admin\AdminController@home'));
Route::get('logout',array('uses' => 'Auth\AuthController@logout'));
Route::get('logout',array('uses' => 'Auth\AuthController@logout'));
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::get('city/{id}',array('uses' => 'UserController@getCity'));
Route::post('password/update', 'Auth\PasswordController@updatePassword');
Route::get('confirm/{token}', 'Auth\AuthController@confirm');
Route::get('refresh-csrf', function(){
    /* http://stackoverflow.com/questions/31449434/handling-expired-token-in-laravel*/
    return csrf_token();
});

