<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorLicenses extends Model
{
    protected $table = 'vendor_licenses';

    protected $fillable = ['expiry_date','license_number','license_image','vendor_id','license_id','created_at','updated_at','category_id'];

    public function license(){
        return $this->belongsTo('App\License','license_id');
    }

    public function vendor(){
        return $this->belongsTo('App\Seller','vendor_id');
    }
    public function category(){
        return $this->belongsTo('App\Category','category_id');
    }
}
