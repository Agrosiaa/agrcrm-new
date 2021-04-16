<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesChat extends Model
{
    protected $table = 'sales_chat';

    protected $fillable = ['user_id','crm_customer_id','message','call_status_id'];
}
