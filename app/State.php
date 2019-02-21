<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    public function city(){
        return $this->hasMany('App\City','state_id');
    }
}
