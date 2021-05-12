<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagCloud extends Model
{
    protected $table = 'tag_cloud';

    protected $fillable = ['name','user_id','tag_type_id','is_active'];
}
