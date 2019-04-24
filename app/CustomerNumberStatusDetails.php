<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerNumberStatusDetails extends Model
{
    protected $table = 'customer_number_status_details';

    protected $fillable = ['customer_number_status_id','user_id','number'];
}
