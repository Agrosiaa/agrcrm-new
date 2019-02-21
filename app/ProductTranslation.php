<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    //products_translation
    protected $table = 'products_translation';

    protected $fillable = ['product_name','key_specs_1','key_specs_2',
        'key_specs_3','search_keywords','created_at','product_description',
        'updated_at','other_features_and_applications','sales_package_or_accessories','domestic_warranty',
        'domestic_warranty_measuring_unit','warranty_summary','warranty_service_type','warranty_items_covered',
        'warranty_items_not_covered','product_id','language_id'

    ];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

}
