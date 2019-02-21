<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductEnquiry extends Model
{
    protected $table = 'product_enquiry';

    protected $fillable = [
        'product_id','mobile','pincode'
    ];
}
