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
    Route::get('/create-lead/{userId}/{number}',array('uses' => 'Crm\CrmController@createLead'));
    Route::post('/set-schedule',array('uses' => 'Crm\CrmController@setSchedule'));
});
Route::group(['prefix' => '/leads'], function () {
    Route::get('/manage/{type}',array('uses' => 'Lead\LeadController@manage'));
    Route::get('/export-customer-number',array('uses' => 'Lead\LeadController@exportCustomerView'));
    Route::post('/export-customer-numbers',array('uses' => 'Lead\LeadController@exportCustomerSheet'));
    Route::post('/assign-customer',array('uses' => 'Lead\LeadController@assignCustomerNumber'));
    Route::post('/sales-lead-listing/{status}',array('uses' => 'Lead\LeadController@saleLeadListing'));
    Route::get('/sales-chat-listing/{id}',array('uses' => 'Lead\LeadController@saleChatListing'));
    Route::post('/sales-chat',array('uses' => 'Lead\LeadController@saleChat'));
    Route::post('/set-reminder',array('uses' => 'Lead\LeadController@setReminder'));
    Route::get('/call-back-status/{custDetailId}',array('uses' => 'Lead\LeadController@callBackStatus'));
    Route::get('/customer-details/{mobile}/{id}',array('uses' => 'Lead\LeadController@CustomerDetailsView'));
    Route::post('/customer-order-listing/{mobile}',array('uses' => 'Lead\LeadController@customerOrderListing'));
});
Route::get('refresh-csrf', function(){
    return csrf_token();
});
Route::group(['prefix' => '/agents'], function () {
    Route::get('manage-agents',array('uses' => 'Admin\AdminController@manageAgents'));
    Route::post('sales-agent-listing',array('uses' => 'Admin\AdminController@salesAgentListing'));
    Route::get('change-agent-status/{id}', array('uses' => 'Admin\AdminController@changeAgentStatus'));
});
