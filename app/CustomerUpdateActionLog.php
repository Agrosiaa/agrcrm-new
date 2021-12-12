<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerUpdateActionLog extends Model
{
    protected $table = 'customer_update_action_log';

    protected $fillable = ['user_id','mobile','field_name','field_value'];

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
