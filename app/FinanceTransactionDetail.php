<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanceTransactionDetail extends Model
{
    protected $table = 'finance_transaction_details';

    protected $fillable = ['finance_doc_order_info_id','transaction_mode_id','amount','transaction_number','transaction_date','deposit_date','reconciled_amount'];

    public function financeDocOrderInfo()
    {
        return $this->hasMany('App\FinanceDocumentOrderInfo','finance_transaction_details_id');
    }
}
