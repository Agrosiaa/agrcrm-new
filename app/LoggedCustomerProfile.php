<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoggedCustomerProfile extends Model
{
    protected $table = 'logged_customer_profile';

    protected $fillable = ['user_id','session_url'];
}
