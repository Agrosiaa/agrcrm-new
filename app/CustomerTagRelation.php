<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerTagRelation extends Model
{
    protected $table = 'customer_tag_relation';

    protected $fillable = ['crm_customer_id','tag_cloud_id'];
}
