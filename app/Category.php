<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Category extends Model implements SluggableInterface
{
    protected $table = 'categories';
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];
    protected $fillable = ['name','slug','tab_name','return_period','description','password','image','meta_title','meta_description','meta_keywords','is_active','is_item_head','item_head_abbreviation','category_id','sku','commission','created_by','created_at','updated_at','name_mr','image_alternate_text','is_configurable','logistic_percentage'];


    public function feature()
    {
        return $this->hasOne('App\Feature','category_id');
    }

    public function brandsCategory()
    {
        return $this->hasMany('App\BrandCategory','category_id');
    }

    public function products(){
        return $this->belongsTo('App\Product','category_id');
    }

    public function productCategoryRel(){
        return $this->hasMany('App\ProductCategoryRelation','category_id');
    }

    public function hsnCodeCategory(){
        return $this->belongsTo('App\HSNCodeTaxRelation','hsn_code_id');
}
}
