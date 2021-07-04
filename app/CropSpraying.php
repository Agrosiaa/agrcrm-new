<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CropSpraying extends Model
{
    protected $table = 'crops_sowed';

    protected $fillable = ['customer_profile_id','crop_sowed_id','pesticide_tag_cloud_id','spraying_number','spraying_date'];

    public function CropSowed()
    {
        return $this->belongsTo('App\CropSowed','crop_sowed_id');
    }

    public function CustomerProfile()
    {
        return $this->belongsTo('App\CustomerProfile','customer_profile_id');
    }

    public function PesticideTag()
    {
        return $this->belongsTo('App\TagCloud','pesticide_tag_cloud_id');
    }
}
