<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeasuringUnit extends Model
{
    protected $table = 'measuring_units';

    public function features()
    {
        return $this->hasMany('App\Feature','measuring_unit_id');
    }
}
