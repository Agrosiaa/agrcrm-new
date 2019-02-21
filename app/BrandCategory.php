<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    protected $table = 'brand_category_relation';
    protected $fillable = ['brand_id','category_id','created_at','updated_at'];

    public function category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function brands()
    {
        return $this->belongsTo('App\Brand','brand_id');
    }
}
