<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Agronomy extends Model implements SluggableInterface
{
    protected $table = "agronomy";
    protected $fillable = ['crop_name','slug','cover_image','crop_image','is_active','crop_name_mr','created_at','updated_at'];
    use SluggableTrait;
    protected $sluggable = [
        'build_from' => 'crop_name',
        'save_to'    => 'slug',
    ];
    public function agronomyInfo()
    {
        return $this->hasMany('App\AgronomyInfo','agronomy_id');
    }
}