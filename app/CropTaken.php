<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CropTaken extends Model
{
    protected $table = 'crop_taken';

    protected $fillable = ['customer_profile_id','crop','year','month','area'];
}
