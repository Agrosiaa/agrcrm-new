<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CropSowed extends Model
{
    protected $table = 'crops_sowed';

    protected $fillable = ['customer_profile_id','crop','sowed_date'];
}
