<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminder';

    protected $fillable = ['call_back_id','customer_number_status_details_id','reminder_time'];
}
