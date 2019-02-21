<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryRelation extends Model
{
    protected $table = 'product_category';

    protected $fillable = [
        'product_id','category_id','created_at','updated_at'
    ];

    public function productCategoryRel(){
        return $this->belongsTo('App\Product','product_id');
    }

    public function CategoryProductRel(){
        return $this->belongsTo('App\Category','category_id');
    }
}
