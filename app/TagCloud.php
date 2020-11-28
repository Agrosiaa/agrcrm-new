<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagCloud extends Model
{
    protected $table = 'tag_cloud';

    protected $fillable = ['name','user_id','is_product','is_category','is_crop','is_active'];
}
