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
Route::group(['prefix' => '/crm'], function () {
    Route::get('/manage',array('uses' => 'Crm\CrmController@manage'));
});
Route::group(['prefix' => '/leads'], function () {
    Route::get('/manage/{type}',array('uses' => 'Lead\LeadController@manage'));
    Route::get('/export-customer-number',array('uses' => 'Lead\LeadController@exportCustomerView'));
    Route::post('/export-customer-numbers',array('uses' => 'Lead\LeadController@exportCustomerSheet'));
    Route::post('/sales-admin-listing/{status}',array('uses' => 'Lead\LeadController@saleAdminListing'));
});
Route::get('refresh-csrf', function(){
    return csrf_token();
});

