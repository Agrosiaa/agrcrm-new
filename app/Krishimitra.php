<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Krishimitra extends Model
{
    protected $table = 'krishimitra';

    protected $fillable = ['user_id','referral_code','mobile','email','aadhar_card_number','aadhar_card',
        'pan_card_number','pan_card','cancelled_cheque','email_verification_token','is_email_verified',
        'is_active','gender','dynamic_link','is_krishimitra','name_of_premise_building_village','area_locality_wadi',
        'road_street_lane','at_post','taluka','district','state','pincode'
    ];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
