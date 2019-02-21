<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogistingAccounting extends Model
{
    protected $table = 'logistic_accounting';

    protected $fillable = ['order_id','biller_id','trans_id','biller_name','amount','commission','gst','net_payable',
                            'article_number','barcode_number','document_number','payment_docket_number','check_number',
                            'payment_date','collection_office','collection_date','logistic_number','logistic_date','note_name',
                            'logistic_invoice_amount','invoice_payment_details','actual_logistic_cost','article_type'];
}
