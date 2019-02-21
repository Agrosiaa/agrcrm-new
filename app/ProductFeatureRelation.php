<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeatureRelation extends Model
{
    protected $table = 'product_feature_relations';

    protected $fillable = [
        'product_id','feature_id','feature_text','feature_measuring_unit',
        'feature_option_id','created_at','updated_at','feature_text_mr'
    ];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

    public function feature(){
        return $this->belongsTo('App\Feature','feature_id');
    }
}
