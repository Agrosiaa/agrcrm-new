<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'product/list','product/queries-data','product/image-upload','operational/products/image-upload','delete-temp-product-image','operational/products/delete-temp-product-image','delete-edit-product-image','product/display-image','operational/products/display-image',
        'product/query-conversation','verification/product/query-conversation','verification/product/query-status','category-product-list','operational/feature/manage-data','operational/feature/check-code','verification/product/bulk-approval','product/query-status',
        'product/category-taxes','product/calculate-price','seller/generate-otp','seller/check-otp','seller/sign-up','seller/check-email','operational/category/check-excel-tab','operational/products/list','category/product-list',
        'operational/products/queries-data','verification/product/queries-data','operational/category/product-list','verification/product/list','operational/vendor/list','product/delete-temp-product-image',
        'operational/products/display-image','operational/products/delete-temp-product-image','operational/products/image-upload','operational/products/query-status','operational/products/query-conversation','operational/products/category-taxes',
        'operational/products/calculate-price','operational/feature/check-name','operational/products/bulk-approval','operational/category/check-category','operational/brand/validate-name','operational/vendor/check-abbreviation',
        'operational/category/check-item-abbreviation','operational/category/check-sku','operational/vendor/check-address','check-address','operational/brand/validate-name/*','verification/product/query-approve-listing','operational/products/query-approve-listing','product/get-vat','operational/products/get-vat',
        'order/list/*','operational/order/list/*','operational/category/check-category/*','my-account/check-email/*','my-account/check-password','/shipment/order/list/*','operational/rma/list/*','/shipment/rma/list/*','/rma/list/*','operational/customer/list','operational/customer/abandonedlist','/shipment/order/payment/list','/finance/order/list','/finance/order/vendor_settlement/list','get-taluka/',
        'customer-support/customer/list','customer-support/order/list/*','customer-support/rma/list/*','vendor-support/order/list/*','vendor-support/rma/list/*','vendor-support/vendor/list','/vendor-support/products/list',
        'data-entry/products/list','data-entry/products/image-upload','data-entry/feature/manage-data','/vendor/list','/vendor/order/list/*',
        '/operational/administration/krishimitra/check-email'
    ];
}
