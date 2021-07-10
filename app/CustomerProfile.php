<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $table = 'customer_profile';

    protected $fillable = ['mobile','full_name','allied_job','communication_lang','mother_tongue',
                           'product_sold_market','total_land','use_microirrigation','income_level',
                           'product_purchase_from','business_job','plant_watering','plant_fertilizer',
                           'plant_seed_purchase_from','plant_used','gardening_type'];

    public function CropsSowed()
    {
        return $this->hasMany('App\CropSowed','customer_profile_id');
    }
}
