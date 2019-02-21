<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotifyUser extends Model
{
    protected $table = 'notify_users';

    protected $fillable = ['mobile','user_id','remark','pincode'];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
