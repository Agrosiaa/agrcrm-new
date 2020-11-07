<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'reminder';

    protected $fillable = ['call_back_id','crm_customer_id','reminder_time','is_schedule'];
}
