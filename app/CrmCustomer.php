<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmCustomer extends Model
{
    protected $table = 'crm_customer';

    protected $fillable = ['customer_number_status_id','user_id','number','lead_source','is_abandoned','is_active'];
}
