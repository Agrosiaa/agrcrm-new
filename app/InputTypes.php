<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputTypes extends Model
{
    protected $table = 'input_type_master';

    public function feature()
    {
        return $this->hasOne('App\Feature','input_type_id');
    }
}
