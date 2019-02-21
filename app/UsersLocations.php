<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersLocations extends Model
{
    protected $table = "users_locations";

    protected $fillable = ['latitude','longitude','reference_id','from_slug','location_action_id','created_at','updated_at'];
}
