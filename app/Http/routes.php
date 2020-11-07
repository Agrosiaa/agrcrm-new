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
Route::get('home',array('uses' => 'Admin\AdminController@home'));
Route::get('logout',array('uses' => 'Auth\AuthController@logout'));
Route::get('logout',array('uses' => 'Auth\AuthController@logout'));
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::get('city/{id}',array('uses' => 'UserController@getCity'));
Route::post('password/update', 'Auth\PasswordController@updatePassword');
Route::get('confirm/{token}', 'Auth\AuthController@confirm');
Route::get('refresh-csrf', function(){
    return csrf_token();
});
Route::group(['prefix' => '/crm'], function () {
    Route::get('/manage',array('uses' => 'Crm\CrmController@manage'));
    Route::get('/csr-orders',array('uses' => 'Crm\CrmController@csrOrders'));
    Route::post('/csr-orders',array('uses' => 'Crm\CrmController@CsrOrderListing'));
    Route::get('/create-lead/{userId}/{number}',array('uses' => 'Crm\CrmController@createLead'));
    Route::post('/set-schedule',array('uses' => 'Crm\CrmController@setSchedule'));
});
Route::group(['prefix' => '/customer'], function () {
    Route::post('/customer-order-listing/{mobile}',array('uses' => 'Customer\CustomerController@customerOrderListing'));
    Route::post('/abandoned-cart/{mobile}',array('uses' => 'Customer\CustomerController@abandonedCartListing'));
    Route::get('/customer-details/{mobile}/{id}',array('uses' => 'Customer\CustomerController@CustomerDetailsView'));
    Route::get('/abandoned-cart-detail/{id}',array('uses' => 'Customer\CustomerController@cartDetails'));
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
    Route::get('/sync-abandoned-cart',array('uses' => 'Lead\LeadController@syncAbandonedCart'));
    Route::get('/remove-lead/{id}',array('uses' => 'Lead\LeadController@removeLead'));
});
Route::group(['prefix' => '/agents'], function () {
    Route::get('manage-agents',array('uses' => 'Admin\AdminController@manageAgents'));
    Route::post('sales-agent-listing',array('uses' => 'Admin\AdminController@salesAgentListing'));
    Route::get('change-agent-status/{id}', array('uses' => 'Admin\AdminController@changeAgentStatus'));
    Route::get('assign-abandoned-cart-agent/{id}', array('uses' => 'Admin\AdminController@assignAbandonedCartAgent'));
});
Route::group(['prefix' => '/report'], function () {
    Route::get('/view',array('uses' => 'Report\ReportController@view'));
    Route::post('/generate',array('uses' => 'Report\ReportController@generateReport'));
});
Route::group(['prefix' => '/tag'], function () {
    Route::get('/manage',array('uses' => 'Tag\TagController@manage'));
    Route::post('/tag-listing',array('uses' => 'Tag\TagController@tagListing'));
    Route::post('/create-edit-tag',array('uses' => 'Tag\TagController@createEditTag'));
});
