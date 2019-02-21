<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SubPopHead extends Model
{
    protected $table = "sub_pop_heads";
    protected $fillable = ['name','name_mr','slug','pop_head_id'];
    public function popHead(){
        return $this->belongsTo('App\PopHead','pop_head_id');
    }
    public function agronomyInfo()
    {
        return $this->hasMany('App\AgronomyInfo','sub_pop_head_id');
    }
}