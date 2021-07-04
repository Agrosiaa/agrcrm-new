<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CropSowed extends Model
{
    protected $table = 'crops_sowed';

    protected $fillable = ['customer_profile_id','crop_tag_cloud_id','crop','sowed_date','cropping_pattern'];

    public function CustomerProfile()
    {
        return $this->belongsTo('App\CustomerProfile','customer_profile_id');
    }

    public function CropTag()
    {
        return $this->belongsTo('App\TagCloud','crop_tag_cloud_id');
    }

    public function CropSpraying()
    {
        return $this->hasMany('App\CropSpraying','crop_sowed_id');
    }
}
