<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HSNCodes extends Model
{
    protected $table = 'hsn_codes';

    protected $fillable = [
        'hsn_code'
    ];
}
