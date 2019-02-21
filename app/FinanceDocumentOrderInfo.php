<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanceDocumentOrderInfo extends Model
{
    protected $table = 'finance_doc_order_info';

    protected $fillable = ['finance_doc_id','order_id','finance_transaction_details_id','reconciled_on'];

    public function transactionDetails(){
        return $this->belongsTo('App\FinanceTransactionDetail','finance_transaction_details_id');
    }

    public function orders(){
        return $this->belongsTo('App\Order','order_id');
    }
}
