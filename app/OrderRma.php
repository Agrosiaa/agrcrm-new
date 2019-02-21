<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderRma extends Model
{
    protected $table = 'order_rma';

    protected $fillable = [
        'account_no','bank_name','branch_name','ifsc_code','reason','product_name','product_sku','return_quantity','order_id',
        'rma_reason_id','customer_id','created_at','updated_at','rma_status_id','neft_number','rma_cancel_text','pick_up_date','return_delivery_date','consignment_number','shipping_method_id'
    ];

    public function rmaReason()
    {
        return $this->belongsTo('App\RmaReason','rma_reason_id');
    }
    public function rmaStatus()
    {
        return $this->belongsTo('App\RmaStatus','rma_status_id');
    }
    public function order()
    {
        return $this->belongsTo('App\Order','order_id');
    }
    public function shippingMethod()
    {
        return $this->belongsTo('App\ShippingMethod','shipping_method_id');
    }
}
