<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pin_codes';

    protected $fillable = ['shipping_company_id','pin_code'];
}
