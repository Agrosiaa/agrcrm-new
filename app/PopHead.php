<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PopHead extends Model
{
    protected $table = "pop_heads";

    protected $fillable = ['name','name_mr','slug'];

    public function subPopHeads(){
        return $this->hasMany('App\SubPopHeads','pop_head_id');
    }
}