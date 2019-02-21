<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderQuantityInfo extends Model
{
    protected $table = "order_quantity_info";

    protected $fillable = ['order_id','batch_number','lot_number','mfg_date','expiry_date'];
}
