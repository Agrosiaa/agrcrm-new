<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model
{
    protected $table = 'customer_used_coupons';

    protected $fillable = [
        'coupon_id','customer_id'
    ];
}
