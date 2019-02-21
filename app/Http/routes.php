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
/* Web Appication Routes For Seller User */
Route::group(['domain' => getenv('SELLER_SUB_DOMAIN_NAME').".".getenv('DOMAIN_NAME')], function () {
    Route::get('login',array('uses' => 'Auth\AuthController@viewLogin'));
    Route::get('/',array('uses' => 'Auth\AuthController@viewLogin'));
    Route::get('home',array('uses' => 'Seller\SellerController@home'));
    Route::get('dashboard',array('uses' => 'Seller\SellerController@home'));
    Route::get('get-notifications',array('uses' => 'Seller\SellerController@getNotification'));
    Route::get('read-notifications',array('uses' => 'Seller\SellerController@readNotification'));
    Route::post('change-language',array('uses' => 'Seller\SellerController@changeLanguage'));
     /* Profile Routes for seller*/
    Route::get('profile',array('uses' => 'Seller\SellerController@viewProfile'));
    Route::post('profile',array('uses' => 'Seller\SellerController@editProfile'));
    Route::post('bank-details',array('uses' => 'Seller\SellerController@updateBankDetails'));
    Route::post('profile-image',array('uses' => 'Seller\SellerController@updateProfileImage'));
    Route::get('get-post-offices',array('uses' => 'Seller\SellerController@getPostOffices'));
    Route::post('add-new-address',array('uses' => 'Seller\SellerController@addNewAddress'));
    Route::get('delete-address/{id}',array('uses' => 'Seller\SellerController@deleteAddress'));
    Route::post('check-address',array('uses' => 'Seller\SellerController@checkAddressAbbreviation'));
    Route::get('get-taluka/{name}',array('uses' => 'Seller\SellerController@getTaluka'));
    Route::post('get-default-address',array('uses' => 'Seller\SellerController@getSellerDefaultAddress'));
    /* Product Routes for seller*/
    Route::group(['prefix' => 'product'], function () {
        Route::get('get-taxes/{hsnCodeId}',array('uses' => 'Seller\ProductController@getTaxes'));
        Route::get('get-allTaxes',array('uses' => 'Seller\ProductController@getAllTaxes'));
        Route::get('manage',array('uses' => 'Seller\ProductController@viewProductList'));
        Route::get('add',array('uses' => 'Seller\ProductController@createProductView'));
        Route::post('add',array('uses' => 'Seller\ProductController@createProduct'));
        Route::get('edit/{id}',array('uses' => 'Seller\ProductController@editProductView'));
        Route::post('edit/{id}',array('uses' => 'Seller\ProductController@editProduct'));
        Route::post('list',array('uses' => 'Seller\ProductController@productListing'));
        Route::post('image-upload',array('uses' => 'Seller\ProductController@imageUpload'));
        Route::post('display-image',array('uses' => 'Seller\ProductController@displayProductImage'));
        Route::post('delete-temp-product-image',array('uses' => 'Seller\ProductController@deleteTempProductImage'));
        Route::post('delete-edit-product-image',array('uses' => 'Seller\ProductController@deleteEditProductImage'));
        Route::get('disable/{id}',array('uses' => 'Seller\ProductController@disableProduct'));
        Route::get('queries',array('uses' => 'Seller\ProductController@productQueryView'));
        Route::post('queries-data',array('uses' => 'Seller\ProductController@productQuery'));
        Route::get('preview/{id}',array('uses' => 'Seller\ProductController@productPreview'));
        Route::post('category-taxes',array('uses' => 'Seller\ProductController@getProductCategoryTaxes'));
        Route::post('calculate-price',array('uses' => 'Seller\ProductController@calculateProductPrice'));
        Route::post('query-conversation/{take?}/{skip?}',array('uses' => 'Seller\ProductController@getProductQueryConversation'));
        Route::post('query-resolved',array('uses' => 'Seller\ProductController@productQueryResolved'));
        Route::post('query-status',array('uses' => 'Seller\ProductController@getProductQueryStatus'));
        Route::post('query-count',array('uses' => 'Seller\ProductController@getProductQueryCount'));
        Route::post('get-vat',array('uses' => 'Seller\ProductController@getProductVat'));
        Route::get('out-of-stock/{id}',array('uses' => 'Seller\ProductController@outOfStock'));
        Route::post('category-taxes-add',array('uses' => 'Seller\ProductController@getAddProductCategoryTaxes'));
    });
    /* Tax Routes for seller*/
    Route::group(['prefix' => 'tax'], function () {
        Route::get('view',array('uses' => 'Seller\SellerController@viewTax'));
    });
    Route::group(['prefix' => 'category'], function () {
        Route::get('{slug}',array('uses' => 'Seller\CategoryController@viewCategory'));
        Route::post('product-list',array('uses' => 'Seller\CategoryController@categoryProductListing'));
    });
    /* Administration Routes */
    Route::group(['prefix' => 'administration'], function () {
        Route::get('export',array('uses' => 'Seller\ExcelController@csvExportView'));
        Route::post('export',array('uses' => 'Seller\ExcelController@csvExport'));
        Route::get('import',array('uses' => 'Seller\ExcelController@csvImportView'));
        Route::post('import',array('uses' => 'Seller\ExcelController@csvImport'));
        Route::post('image',array('uses' => 'Seller\ExcelController@imageUpload'));
    });

    /* Orders Routes */
    Route::group(['prefix' => 'order'], function () {
        Route::get('manage/{type}',array('uses' => 'Seller\OrderController@viewOrderList'));
        Route::post('list/{orderStatus}',array('uses' => 'Seller\OrderController@orderListing'));
        Route::post('get-packing-checklist-data',array('uses' => 'Seller\OrderController@getPackingCheckListData'));
        Route::get('view/{id}',array('uses' => 'Seller\OrderController@orderDetailView'));
        Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'Seller\OrderController@changeOrderStatus'));
        Route::post('cancel-order/{id}',array('uses' => 'Seller\OrderController@cancelOrder'));
        Route::post('get-manifest-checklist-data',array('uses' => 'Seller\OrderController@getManifestCheckListData'));
        Route::post('checklist-preview',array('uses' => 'Seller\OrderController@checklistPreview'));
        Route::get('invoice/{id}',array('uses' => 'Seller\OrderController@generateInvoice'));
        Route::post('logistics-preview',array('uses' => 'Seller\OrderController@logisticsPreview'));
        Route::post('save-expiry-date',array('uses' => 'Seller\OrderController@saveExpiryDates'));

    });


    Route::group(['prefix' => 'rma'], function () {
        Route::get('manage/{type}',array('uses' => 'Seller\RmaController@viewRmaList'));
        Route::post('list/{rmaStatus}',array('uses' => 'Seller\RmaController@rmaListing'));
        Route::get('view/{id}',array('uses' => 'Seller\RmaController@rmaDetailView'));
        Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'Seller\RmaController@changeRmaStatus'));
    });

    /*Report Routes*/
    Route::group(['prefix' => 'report'], function (){
      Route::get('view',array('uses' => 'Seller\ReportController@view'));
      Route::post('get-detail' ,array('uses' => 'Seller\ReportController@generateReport'));
    });

});
/* Web Appication Routes For Verification/Operational Admin User */
Route::group(['domain' => getenv('ADMIN_SUB_DOMAIN_NAME').".".getenv('DOMAIN_NAME')], function () {
    Route::get('/',array('uses' => 'Auth\AuthController@viewAdminLogin'));
    Route::get('home',array('uses' => 'Admin\AdminController@home'));
    Route::get('dashboard',array('uses' => 'Admin\AdminController@home'));
    Route::post('change-language',array('uses' => 'Admin\AdminController@changeLanguage'));
    Route::get('get-taluka/{name}',array('uses' => 'Superadmin\VendorController@getTaluka'));
    Route::group(['prefix' => 'verification'], function () {
        Route::group(['prefix' => 'product'], function () {
            Route::get('manage',array('uses' => 'Admin\ProductController@viewProductList'));
            Route::post('list',array('uses' => 'Admin\ProductController@productListing'));
            Route::get('queries',array('uses' => 'Admin\ProductController@productQueryView'));
            Route::get('edit/{id}',array('uses' => 'Admin\ProductController@editProductView'));
            Route::post('queries-data',array('uses' => 'Admin\ProductController@productQuery'));
            Route::post('query-conversation/{take?}/{skip?}',array('uses' => 'Admin\ProductController@getProductQueryConversation'));
            Route::post('query-resolved',array('uses' => 'Admin\ProductController@productQueryResolved'));
            Route::post('query-raised',array('uses' => 'Admin\ProductController@productQueryRaised'));
            Route::post('approve',array('uses' => 'Admin\ProductController@approveProduct'));
            Route::post('bulk-approval',array('uses' => 'Admin\ProductController@approveBulkProduct'));
            Route::post('query-status',array('uses' => 'Admin\ProductController@getProductQueryStatus'));
            Route::get('preview/{id}',array('uses' => 'Admin\ProductController@productPreview'));
        });
    });
    Route::group(['prefix' => 'operational'], function () {
    /*Logistic Excel Export*/
    Route::group(['prefix' => 'logistic'], function () {
            Route::get('export-for-logistic',array('uses' => 'Superadmin\LogisticExcellController@logisticExcelView'));
            Route::post('export-for-logistic',array('uses' => 'Superadmin\LogisticExcellController@logisticExcelImport'));
        });
        /*Superadmin Routes */
        Route::group(['prefix' => 'feature'], function () {
            Route::get('create',array('uses' => 'Superadmin\FeatureController@createView'));
            Route::post('create',array('uses' => 'Superadmin\FeatureController@create'));
            Route::get('manage',array('uses' => 'Superadmin\FeatureController@manageView'));
            Route::post('manage-data',array('uses' => 'Superadmin\FeatureController@manageFeatureData'));
            Route::get('edit/{id}',array('uses' => 'Superadmin\FeatureController@editView'));
            Route::put('edit/{id}',array('uses' => 'Superadmin\FeatureController@edit'));
            Route::post('check-code',array('uses' => 'Superadmin\FeatureController@checkCode'));
            Route::post('check-name',array('uses' => 'Superadmin\FeatureController@checkFeatureName'));
            Route::put('full-edit/{id}',array('uses' => 'Superadmin\FeatureController@fullEdit'));
        });
        /* super admin route for category*/
        Route::group(['prefix' => 'category'], function () {
            Route::get('/',array('uses' => 'Superadmin\CategoryController@view'));
            Route::get('/{slug}',array('uses' => 'Superadmin\CategoryController@view'));
            Route::post('create-root',array('uses' => 'Superadmin\CategoryController@createRoot'));
            Route::post('check-excel-tab',array('uses' => 'Superadmin\CategoryController@checkTabName'));
            Route::get('get-sub/{category_id}',array('uses' => 'Superadmin\CategoryController@getSub'));
            Route::post('create-sub',array('uses' => 'Superadmin\CategoryController@createSub'));
            Route::put('edit/{category_id}',array('uses' => 'Superadmin\CategoryController@edit'));
            Route::post('product-list',array('uses' => 'Superadmin\CategoryController@categoryProductListing'));
            Route::post('check-category',array('uses' => 'Superadmin\CategoryController@checkCategoryName'));
            Route::post('check-item-abbreviation',array('uses' => 'Superadmin\CategoryController@checkItemAbbreviation'));
            Route::post('check-sku',array('uses' => 'Superadmin\CategoryController@checkSku'));
            Route::post('check-category/{id}',array('uses' => 'Superadmin\CategoryController@checkCategoryNameEdit'));
            Route::get('/hsn-code/{keyword}',array('uses' => 'Superadmin\CategoryController@getHSNCode'));
        });
        Route::group(['prefix' => 'brand'], function () {
            Route::get('create',array('uses' => 'Superadmin\BrandController@createView'));
            Route::post('create',array('uses' => 'Superadmin\BrandController@create'));
            Route::post('edit',array('uses' => 'Superadmin\BrandController@editRoute'));
            Route::put('edit/{id}',array('uses' => 'Superadmin\BrandController@edit'));
            Route::get('edit/{id}',array('uses' => 'Superadmin\BrandController@editView'));
            Route::get('item-heads',array('uses' => 'Superadmin\BrandController@itemHeads'));
            Route::get('item-heads/{id}',array('uses' => 'Superadmin\BrandController@itemHeads'));
            Route::post('validate-name',array('uses' => 'Superadmin\BrandController@checkBrandName'));
            Route::post('validate-name/{id}',array('uses' => 'Superadmin\BrandController@checkBrandNameEdit'));
            Route::get('all',array('uses' => 'Superadmin\BrandController@getAll'));
            //Route::get('dump-brand',array('uses' => 'Superadmin\BrandController@dumpBrand'));

        });
        Route::group(['prefix' => 'products'], function () {
            Route::get('manage',array('uses' => 'Superadmin\ProductController@viewProductList'));
            Route::post('list',array('uses' => 'Superadmin\ProductController@productListing'));
            Route::get('edit/{id}',array('uses' => 'Superadmin\ProductController@editProductView'));
            Route::post('edit/{id}',array('uses' => 'Superadmin\ProductController@editProduct'));
            Route::get('get-taxes/{hsnCodeId}',array('uses' => 'Superadmin\ProductController@getTaxes'));
            Route::get('get-allTaxes',array('uses' => 'Superadmin\ProductController@getAllTaxes'));
            Route::get('queries',array('uses' => 'Superadmin\ProductController@productQueryView'));
            Route::post('queries-data',array('uses' => 'Superadmin\ProductController@productQuery'));
            Route::get('create',array('uses' => 'Superadmin\ProductController@createView'));
            Route::post('create',array('uses' => 'Superadmin\ProductController@createProduct'));
            Route::post('query-conversation/{take?}/{skip?}',array('uses' => 'Superadmin\ProductController@getProductQueryConversation'));
            Route::post('query-resolved',array('uses' => 'Superadmin\ProductController@productQueryResolved'));
            Route::post('query-status',array('uses' => 'Superadmin\ProductController@getProductQueryStatus'));
            Route::post('query-raised',array('uses' => 'Superadmin\ProductController@productQueryRaised'));
            Route::get('query-count',array('uses' => 'Superadmin\ProductController@getProductQueryCount'));
            Route::get('preview/{id}',array('uses' => 'Superadmin\ProductController@productPreview'));
            Route::post('image-upload',array('uses' => 'Superadmin\ProductController@imageUpload'));
            Route::post('display-image',array('uses' => 'Superadmin\ProductController@displayProductImage'));
            Route::post('delete-temp-product-image',array('uses' => 'Superadmin\ProductController@deleteTempProductImage'));
            Route::post('delete-edit-product-image',array('uses' => 'Superadmin\ProductController@deleteEditProductImage'));
            Route::post('category-taxes',array('uses' => 'Superadmin\ProductController@getProductCategoryTaxes'));
            Route::post('calculate-price',array('uses' => 'Superadmin\ProductController@calculateProductPrice'));
            Route::get('approve/{productId}',array('uses' => 'Superadmin\ProductController@approveProduct'));
            Route::post('bulk-approval',array('uses' => 'Superadmin\ProductController@approveBulkProduct'));
            Route::get('get-address/{id}',array('uses' => 'Superadmin\ProductController@getAddress'));
            Route::get('out-of-stock/{id}',array('uses' => 'Superadmin\ProductController@outOfStock'));
            Route::post('category-taxes-add',array('uses' => 'Superadmin\ProductController@getAddProductCategoryTaxes'));
        });
        Route::group(['prefix' => 'vendor'], function () {
            Route::group(['prefix' => 'licence'], function () {
                Route::get('get/{licName}/{id}',array('uses' => 'Superadmin\VendorController@getLicenceInformation'));
                Route::post('edit/{id}',array('uses' => 'Superadmin\VendorController@editLicenceInformation'));
                Route::post('assign-category-license/{id}',array('uses' => 'Superadmin\VendorController@assignCategoryToLicense'));
                Route::get('delete-vendor-license/{id}',array('uses' => 'Superadmin\VendorController@deleteVendorLicence'));
                Route::post('add-new-license/{id}',array('uses' => 'Superadmin\VendorController@AddNewLicence'));
            });
            Route::get('manage',array('uses' => 'Superadmin\VendorController@viewVendorList'));
            Route::post('list',array('uses' => 'Superadmin\VendorController@vendorListing'));
            Route::get('profile/{id}',array('uses' => 'Superadmin\VendorController@viewProfile'));
            Route::post('profile/{id}',array('uses' => 'Superadmin\VendorController@editProfile'));
            Route::post('bank-details/{id}',array('uses' => 'Superadmin\VendorController@updateBankDetails'));
            Route::get('get-post-offices',array('uses' => 'Superadmin\VendorController@getPostOffices'));
            Route::post('add-new-address/{id}',array('uses' => 'Superadmin\VendorController@addNewAddress'));
            Route::post('edit-company/{id}',array('uses' => 'Superadmin\VendorController@editCompanyInformation'));
            Route::post('update-documents/{id}',array('uses' => 'Superadmin\VendorController@editDocuments'));
            Route::post('approve/{id}',array('uses' => 'Superadmin\VendorController@approveVendor'));
            Route::post('check-abbreviation',array('uses' => 'Superadmin\VendorController@checkAbbreviation'));
            Route::post('check-address',array('uses' => 'Superadmin\VendorController@checkAddressAbbreviation'));
            Route::post('get-default-address', array('uses' => 'Superadmin\VendorController@getSellerDefaultAddress'));
        });
        /* Tax Routes for Superadmin*/
        Route::group(['prefix' => 'tax'], function () {
            Route::get('view',array('uses' => 'Superadmin\TaxController@viewTax'));
        });
        /*Report Routes for Superadmin*/
        Route::group(['prefix' => 'report'], function () {
            Route::get('view',array('uses' => 'Superadmin\ReportController@view'));
            Route::post('get-detail' ,array('uses' => 'Superadmin\ReportController@generateReport'));
        });
        /* Orders Routes */
        Route::group(['prefix' => 'order'], function () {
            Route::post('pending-order/{id}',array('uses' => 'Superadmin\OrderController@pendingOrder'));
            Route::post('agrosiaaShipment-pending-Order/{id}',array('uses' => 'Superadmin\OrderController@agrosiaaShipmentPendingOrder'));
            Route::post('update-close-Order/{id}',array('uses' => 'Superadmin\OrderController@updateCloseOrder'));
            Route::post('update-pending-order/{id}',array('uses' => 'Superadmin\OrderController@updatePendingOrder'));
            Route::post('update-close-Order-AgrosiaaShipment/{id}',array('uses' => 'Superadmin\OrderController@updateCloseOrderAgrosiaaShipment'));
            Route::post('update-agrosiaaShipment-pending-Order/{id}',array('uses' => 'Superadmin\OrderController@updateAgrosiaaShipmentPendingOrder'));
            Route::get('manage/{type}',array('uses' => 'Superadmin\OrderController@viewOrderList'));
            Route::post('list/{orderStatus}',array('uses' => 'Superadmin\OrderController@orderListing'));
            Route::get('view/{id}',array('uses' => 'Superadmin\OrderController@orderDetailView'));
            Route::post('get-packing-checklist-data',array('uses' => 'Superadmin\OrderController@getPackingCheckListData'));
            Route::post('get-pickup-schedule-data/{orderType}',array('uses' => 'Superadmin\OrderController@getPickupScheduleListData'));
            Route::post('checklist-preview',array('uses' => 'Superadmin\OrderController@checklistPickupSchedulePreview'));
            Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'Superadmin\OrderController@changeOrderStatus'));
            Route::post('edit/{id}',array('uses' => 'Superadmin\OrderController@editInformation'));
            Route::post('cancel-order/{id}',array('uses' => 'Superadmin\OrderController@cancelOrder'));
            Route::post('save-expiry-date',array('uses' => 'Superadmin\OrderController@saveExpiryDates'));
            Route::get('invoice/{id}',array('uses' => 'Superadmin\OrderController@generateInvoice'));
            Route::post('return-to-vendor-rma/{id}',array('uses' => 'Superadmin\OrderController@returnToVendorRma'));
            Route::post('update-work-status/{id}',array('uses' => 'Superadmin\OrderController@updateOrderWorkStatus'));
            Route::post('cancel-order-rtv/{slug}/{id}',array('uses' => 'Superadmin\OrderController@cancleOrderReturnToVendor'));
        });
        /* Administration Routes */
        Route::group(['prefix' => 'administration'], function () {
            Route::get('export-for-hsn-code',array('uses' => 'Superadmin\ExcelController@csvExportForHSNCode'));
            Route::get('import-for-hsn-code',array('uses' => 'Superadmin\ExcelController@csvImportViewForHSNCode'));
            Route::post('import-for-hsn-code',array('uses' => 'Superadmin\ExcelController@csvImportForHSNCode'));
            Route::get('export',array('uses' => 'Superadmin\ExcelController@csvExportView'));
            Route::post('export',array('uses' => 'Superadmin\ExcelController@csvExport'));
            Route::get('import',array('uses' => 'Superadmin\ExcelController@csvImportView'));
            Route::post('import',array('uses' => 'Superadmin\ExcelController@csvImport'));
            Route::post('image',array('uses' => 'Superadmin\ExcelController@imageUpload'));
            Route::group(['prefix' => 'agronomy'],function(){
                Route::get('create',array('uses' => 'Superadmin\AgronomyController@createAgronomyView'));
                Route::post('create',array('uses' => 'Superadmin\AgronomyController@createAgronomy'));
                Route::get('edit/{agronomy_slug?}',array('uses' => 'Superadmin\AgronomyController@editAgronomyView'));
                Route::post('edit',array('uses' => 'Superadmin\AgronomyController@editAgronomy'));
                Route::get('approve/{agronomy_slug}',array('uses' => 'Superadmin\AgronomyController@approve'));
                Route::get('preview/{agronomy_slug}',array('uses' => 'Superadmin\AgronomyController@preview'));

            });
            Route::group(['prefix' => 'krishimitra'], function(){
                Route::get('manage',array('uses' => 'Superadmin\KrishimitraController@getManageView'));
                Route::get('create',array('uses' => 'Superadmin\KrishimitraController@getRegisterView'));
                Route::post('register',array('uses' => 'Superadmin\KrishimitraController@registerKrishimitra'));
                Route::post('check-mobile',array('uses' => 'Superadmin\KrishimitraController@checkKrishimitraMobile'));
                Route::post('check-email',array('uses' => 'Superadmin\KrishimitraController@checkEmail'));
                Route::post('validate-otp',array('uses' => 'User\RegistrationController@validateOtp'));
                Route::get('get-post-office-info/{id}',array('uses' => 'Superadmin\KrishimitraController@getPostOfficeInfo'));
                Route::get('get-pincode',array('uses' => 'Superadmin\KrishimitraController@getPincode'));
                Route::post('listing',array('uses' => 'Superadmin\KrishimitraController@listing'));
                Route::get('change-status/{newStatus}/{krishimitraId}',array('uses' => 'Superadmin\KrishimitraController@changeStatus'));
                Route::get('edit/{krishimitraId}',array('uses' => 'Superadmin\KrishimitraController@getEditView'));
                Route::post('edit/{krishimitraId}',array('uses' => 'Superadmin\KrishimitraController@editKrishimitra'));
            });
        });

        Route::group(['prefix' => 'postoffice'], function () {
            Route::get('view',array('uses' => 'Superadmin\PostOfficeController@view'));
            Route::post('import',array('uses' => 'Superadmin\PostOfficeController@csvImport'));
        });
        /* Orders Routes */
        Route::group(['prefix' => 'rma'], function () {
            Route::get('manage/{type}',array('uses' => 'Superadmin\RmaController@viewRmaList'));
            Route::post('list/{rmaStatus}',array('uses' => 'Superadmin\RmaController@rmaListing'));
            Route::get('view/{id}',array('uses' => 'Superadmin\RmaController@rmaDetailView'));
            Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'Superadmin\RmaController@changeRmaStatus'));
            Route::post('initiate-rma',array('uses' => 'Superadmin\RmaController@initiateRma'));
            Route::post('edit-neft/{id}',array('uses' => 'Superadmin\RmaController@updateNeftNumber'));
            Route::get('invoice/{id}',array('uses' => 'Superadmin\RmaController@generateInvoice'));
            Route::post('edit-consignment/{id}' ,array('uses' => 'Superadmin\RmaController@updateConsignmentNumber'));
        });
        Route::group(['prefix' => 'customer'], function () {
            Route::get('manage',array('uses' => 'Superadmin\CustomerController@viewCustomerList'));
            Route::post('list',array('uses' => 'Superadmin\CustomerController@customerListing'));
            Route::get('profile/{id}',array('uses' => 'Superadmin\CustomerController@viewProfile'));
            Route::post('profile/{id}',array('uses' => 'Superadmin\CustomerController@editProfile'));
            Route::get('abandoned',array('uses' => 'Superadmin\CustomerController@abandoned'));
            Route::post('abandonedlist',array('uses' => 'Superadmin\CustomerController@abandonedListing'));
            Route::get('detail/{id}',array('uses' => 'Superadmin\CustomerController@abandonedDetails'));
        });
    });
    Route::group(['prefix' => 'customer-support'], function(){
        Route::group(['prefix' => 'customer'], function(){
            Route::get('manage',array('uses' => 'CustomerSupport\CustomerController@viewCustomerList'));
            Route::post('list',array('uses' => 'CustomerSupport\CustomerController@customerListing'));

        });
        Route::group(['prefix' => 'order'],function(){
            Route::get('manage/{type}',array('uses' => 'CustomerSupport\OrderController@viewOrderList'));
            Route::post('list/{orderStatus}',array('uses' => 'CustomerSupport\OrderController@orderListing'));
            Route::get('view/{id}',array('uses' => 'CustomerSupport\OrderController@orderDetailView'));
        });
        Route::group(['prefix' => 'rma'], function () {
            Route::get('manage/{type}',array('uses' => 'CustomerSupport\RmaController@viewRmaList'));
            Route::post('list/{rmaStatus}',array('uses' => 'CustomerSupport\RmaController@rmaListing'));
            Route::get('view/{id}',array('uses' => 'CustomerSupport\RmaController@rmaDetailView'));
        });
    });
    Route::group(['prefix' => 'vendor-support'], function(){
        Route::group(['prefix' => 'order'],function(){
            Route::get('manage/{type}',array('uses' => 'VendorSupport\OrderController@viewOrderList'));
            Route::post('list/{orderStatus}',array('uses' => 'VendorSupport\OrderController@orderListing'));
            Route::get('view/{id}',array('uses' => 'VendorSupport\OrderController@orderDetailView'));
        });
        Route::group(['prefix' => 'rma'], function () {
            Route::get('manage/{type}',array('uses' => 'VendorSupport\RmaController@viewRmaList'));
            Route::post('list/{rmaStatus}',array('uses' => 'VendorSupport\RmaController@rmaListing'));
            Route::get('view/{id}',array('uses' => 'VendorSupport\RmaController@rmaDetailView'));
        });
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('manage',array('uses' => 'VendorSupport\VendorController@viewVendorList')); //used
            Route::post('list',array('uses' => 'VendorSupport\VendorController@vendorListing'));//used
            Route::get('profile/{id}',array('uses' => 'VendorSupport\VendorController@viewProfile')); //used
        });
        Route::group(['prefix' => 'products'], function () {
            Route::get('manage',array('uses' => 'VendorSupport\ProductController@viewProductList'));
            Route::post('list',array('uses' => 'VendorSupport\ProductController@productListing'));
            Route::get('edit/{id}',array('uses' => 'VendorSupport\ProductController@editProductView'));
            Route::get('preview/{id}',array('uses' => 'VendorSupport\ProductController@productPreview'));
        });
    });
});
Route::group(['domain' => getenv('SHIPMENT_SUB_DOMAIN_NAME').".".getenv('DOMAIN_NAME')], function () {
    Route::get('/',array('uses' => 'Auth\AuthController@viewShipmentAdminLogin'));
    Route::get('dashboard',array('uses' => 'ShipmentAdmin\ShipmentAdminController@home'));
    Route::get('get-notifications',array('uses' => 'ShipmentAdmin\ShipmentAdminController@getNotification'));
    Route::get('read-notifications',array('uses' => 'ShipmentAdmin\ShipmentAdminController@readNotification'));
    Route::get('home',array('uses' => 'ShipmentAdmin\ShipmentAdminController@home'));
    Route::group(['prefix' => 'shipment'], function () {
         Route::group(['prefix' => 'order'], function () {
            Route::get('notify-pick-up/{id}',array('uses' => 'ShipmentAdmin\OrderController@NotifyPickUp'));
            Route::get('notify-delivery/{id}',array('uses' => 'ShipmentAdmin\OrderController@NotifyDelivery'));
            Route::get('manage/{type}',array('uses' => 'ShipmentAdmin\OrderController@viewOrderList'));
            Route::post('list/{orderStatus}',array('uses' => 'ShipmentAdmin\OrderController@orderListing'));
            Route::get('view/{id}',array('uses' => 'ShipmentAdmin\OrderController@orderDetailView'));
            Route::post('get-packing-checklist-data',array('uses' => 'ShipmentAdmin\OrderController@getPackingCheckListData'));
            Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'ShipmentAdmin\OrderController@changeOrderStatus'));
            Route::post('refuse-order/{slug}/{id}/{currentStatus}',array('uses' => 'ShipmentAdmin\OrderController@changeOrderStatus'));
            Route::post('edit/{id}',array('uses' => 'ShipmentAdmin\OrderController@editInformation'));
            Route::post('cancel-order/{id}',array('uses' => 'ShipmentAdmin\OrderController@cancelOrder'));
            Route::get('invoice/{id}',array('uses' => 'ShipmentAdmin\OrderController@generateInvoice'));
            Route::group(['prefix' => 'payment'], function () {
                 Route::get('manage/{type}',array('uses' => 'ShipmentAdmin\OrderController@viewPaymentCollectList'));
                 Route::post('list',array('uses' => 'ShipmentAdmin\OrderController@orderPaymentListing'));
                 Route::post('make-receipt',array('uses' => 'ShipmentAdmin\OrderController@makePaymentReceipt'));
             });
             Route::post('document',array('uses' => 'ShipmentAdmin\OrderController@saveTransactionDetails'));
        });

        Route::group(['prefix' => 'rma'], function () {
            Route::get('manage/{type}',array('uses' => 'ShipmentAdmin\RmaController@viewRmaList'));
            Route::post('list/{rmaStatus}',array('uses' => 'ShipmentAdmin\RmaController@rmaListing'));
            Route::post('edit/{id}',array('uses' => 'ShipmentAdmin\RmaController@updateConsignmentNumber'));
            Route::get('view/{id}',array('uses' => 'ShipmentAdmin\RmaController@rmaDetailView'));
            Route::get('notify-pick-up/{id}',array('uses' => 'ShipmentAdmin\RmaController@NotifyPickUp'));
            Route::get('notify-delivery/{id}',array('uses' => 'ShipmentAdmin\RmaController@NotifyDelivery'));
            Route::get('invoice/{id}',array('uses' => 'ShipmentAdmin\RmaController@generateInvoice'));

        });
    });
});


Route::group(['domain' => getenv('FINANCE_SUB_DOMAIN_NAME').".".getenv('DOMAIN_NAME')], function () {
    Route::get('/',array('uses' => 'Auth\AuthController@viewFinanceAdminLogin'));
    Route::get('dashboard',array('uses' => 'FinanceAdmin\FinanceAdminController@home'));
    Route::get('get-notifications',array('uses' => 'FinanceAdmin\FinanceAdminController@getNotification'));
    Route::get('read-notifications',array('uses' => 'FinanceAdmin\FinanceAdminController@readNotification'));
    Route::get('home',array('uses' => 'FinanceAdmin\FinanceAdminController@home'));

    /* Orders Routes */
    Route::group(['prefix' => 'finance'], function () {
        Route::group(['prefix' => 'order'], function () {
            Route::get('manage/{type}',array('uses' => 'FinanceAdmin\OrderController@viewOrderList'));
            Route::post('list',array('uses' => 'FinanceAdmin\OrderController@orderListingShipment'));
            Route::get('view/{id}',array('uses' => 'FinanceAdmin\OrderController@orderDetailView'));
            Route::post('get-packing-checklist-data',array('uses' => 'FinanceAdmin\OrderController@getPackingCheckListData'));
            Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'FinanceAdmin\OrderController@changeOrderStatus'));
            Route::post('edit/{id}',array('uses' => 'FinanceAdmin\OrderController@editInformation'));
            Route::post('reconcile/{id}',array('uses' => 'FinanceAdmin\OrderController@reconcileOrder'));
            Route::post('cancel-order/{id}',array('uses' => 'FinanceAdmin\OrderController@cancelOrder'));
            Route::get('invoice/{id}',array('uses' => 'FinanceAdmin\OrderController@generateInvoice'));
            Route::get('document/{doc_type}/{order_id}',array('uses' => 'FinanceAdmin\OrderController@viewFinanceDocuments'));
            Route::get('citrus-receipt-voucher/{orderId}',array('uses' => 'FinanceAdmin\OrderController@citrusReceiptVoucher'));
            Route::group(['prefix' => 'vendor_settlement'],function(){
                Route::get('manage/{type}',array('uses' => 'FinanceAdmin\OrderController@viewVendorSettlementOrderList'));
                Route::post('list',array('uses' => 'FinanceAdmin\OrderController@orderListingVendorSettlement'));
                Route::post('transaction/{orderId}',array('uses' => 'FinanceAdmin\OrderController@saveSettlementTransactionDetails'));
            });
            Route::get('create-payment-advices',array('uses' => 'FinanceAdmin\OrderController@getNotSettledOrders'));

        });
    });
});
Route::group(['domain' => getenv('ACCOUNT_SUB_DOMAIN_NAME').".".getenv('DOMAIN_NAME')], function () {
    Route::get('/',array('uses' => 'Auth\AuthController@viewAccountAdminLogin'));
    Route::get('dashboard',array('uses' => 'AccountAdmin\AccountAdminController@home'));
    Route::group(['prefix' => 'vendor'], function () {
        Route::group(['prefix' => 'licence'], function () {
            Route::get('get/{licName}/{id}',array('uses' => 'AccountAdmin\VendorController@getLicenceInformation'));
            Route::post('edit/{id}',array('uses' => 'AccountAdmin\VendorController@editLicenceInformation'));
            Route::post('assign-category-license/{id}',array('uses' => 'AccountAdmin\VendorController@assignCategoryToLicense'));
            Route::get('delete-vendor-license/{id}',array('uses' => 'AccountAdmin\VendorController@deleteVendorLicence'));
            Route::post('add-new-license/{id}',array('uses' => 'AccountAdmin\VendorController@AddNewLicence'));
        });
        Route::get('manage',array('uses' => 'AccountAdmin\VendorController@viewVendorList'));//used
        Route::post('list',array('uses' => 'AccountAdmin\VendorController@vendorListing'));//used
        Route::get('profile/{id}',array('uses' => 'AccountAdmin\VendorController@viewProfile')); //used
        Route::post('profile/{id}',array('uses' => 'AccountAdmin\VendorController@editProfile'));
        Route::post('bank-details/{id}',array('uses' => 'AccountAdmin\VendorController@updateBankDetails'));
        Route::get('get-post-offices',array('uses' => 'AccountAdmin\VendorController@getPostOffices'));
        Route::post('add-new-address/{id}',array('uses' => 'AccountAdmin\VendorController@addNewAddress'));
        Route::post('edit-company/{id}',array('uses' => 'AccountAdmin\VendorController@editCompanyInformation'));
        Route::post('update-documents/{id}',array('uses' => 'AccountAdmin\VendorController@editDocuments'));
        Route::post('approve/{id}',array('uses' => 'AccountAdmin\VendorController@approveVendor'));
        Route::post('check-abbreviation',array('uses' => 'AccountAdmin\VendorController@checkAbbreviation'));
        Route::post('check-address',array('uses' => 'AccountAdmin\VendorController@checkAddressAbbreviation'));
        Route::post('get-default-address', array('uses' => 'AccountAdmin\VendorController@getSellerDefaultAddress'));

        Route::group(['prefix' => 'order'], function () {
            Route::get('manage/{type}', array('uses' => 'AccountAdmin\OrderController@viewOrderList'));
            Route::post('list/{orderStatus}',array('uses' => 'AccountAdmin\OrderController@orderListing'));
            Route::get('view/{id}',array('uses' => 'AccountAdmin\OrderController@orderDetailView'));
            Route::post('get-packing-checklist-data',array('uses' => 'AccountAdmin\OrderController@getPackingCheckListData'));
            Route::get('change-status/{slug}/{id}/{currentStatus}',array('uses' => 'AccountAdmin\OrderController@changeOrderStatus'));
            Route::post('save-expiry-date',array('uses' => 'AccountAdmin\OrderController@saveExpiryDates'));
            Route::get('invoice/{id}',array('uses' => 'AccountAdmin\OrderController@generateInvoice'));
            Route::post('edit/{id}',array('uses' => 'AccountAdmin\OrderController@editInformation'));
            Route::post('return-to-vendor-rma/{id}',array('uses' => 'AccountAdmin\OrderController@returnToVendorRma'));
            Route::post('cancel-order-rtv/{slug}/{id}',array('uses' => 'AccountAdmin\OrderController@cancleOrderReturnToVendor'));
        });
    });
    Route::group(['prefix' => 'report'], function () {
        Route::get('view',array('uses' => 'AccountAdmin\ReportController@view'));
        Route::post('get-detail' ,array('uses' => 'AccountAdmin\ReportController@generateReport'));
    });

});
