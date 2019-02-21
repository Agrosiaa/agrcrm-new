<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorSettleMent extends Model
{
    protected $table = 'vendor_settlement';

    protected $fillable = ['order_amount', 'order_quantity', 'delivery_charges','commission_percent',
        'commission_amount','order_logistics_charges','return_order_amount','return_order_quantity',
        'final_vendor_settlement_amount','order_complete_date','rma_complete_date',
        'order_id','rma_id','created_at','updated_at','return_logistics_charges','order_vendor_settlement_amount',
        'return_vendor_settlement_amount','order_tcs_amount','return_tcs_amount'];

    public function order()
    {
        return $this->belongsTo('App\Order','order_id');
    }

    public function rma()
    {
        return $this->belongsTo('App\OrderRma','rma_id');
    }
}
