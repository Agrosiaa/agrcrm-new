<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $table = 'customer_profile';

    protected $fillable = ['mobile','full_name','communication_lang','mother_tongue','cropping_pattern',
                           'product_sold_market','total_land','use_microirrigation'];
}
