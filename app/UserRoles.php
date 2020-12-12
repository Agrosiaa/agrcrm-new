<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = "roles";

    protected $fillable = ['name','slug'];
}
