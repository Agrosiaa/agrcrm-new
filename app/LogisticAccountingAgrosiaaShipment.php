<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogisticAccountingAgrosiaaShipment extends Model
{
    protected $table = 'logistic_accounting_agrosiaa_shipment';

    protected $fillable = ['order_id','deliver_by','delivery_done_by','lr_number','lr_date','lr_amount','payment_received_mode','bank_name','payment_deposit_date','deposit_note','invoice_number','invoice_date','invoice_amount'];
}
