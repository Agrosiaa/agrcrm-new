<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerTagRelation extends Model
{
    protected $table = 'customer_tag_relation';

    protected $fillable = ['user_id','is_deleted','crm_customer_id','tag_cloud_id','deleted_datetime','deleted_tag_user'];
}
