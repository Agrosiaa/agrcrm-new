<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RtvMicroStatusDetails extends Model
{
    protected $table = "rtv_micro_status_details";

    protected $fillable =['rtv_micro_status_id','order_id','reconcile_order_number'];
}
