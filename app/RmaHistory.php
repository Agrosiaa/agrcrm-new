<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RmaHistory extends Model
{
    protected $table = 'rma_history';

    protected $fillable = ['is_email_sent','rma_id','rma_status_id','user_id','created_at','updated_at'];
    public function cancel()
    {
        return $this->belongsTo('App\CancelReason','customer_cancel_reasons_id');
    }
}
