<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table = "subscriptions";

    protected $fillable = ['email','created_at','updated_at','mobile'];
}
