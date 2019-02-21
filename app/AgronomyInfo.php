<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class AgronomyInfo extends Model
{
    protected $table = "agronomy_info";
    protected $fillable = ['agronomy_id','sub_pop_head_id','language_id','information'];
    public function agronomy()
    {
        return $this->belongsTo('App\Agronomy','agronomy_id');
    }
    public function subPopHeads()
    {
        return $this->belongsTo('App\SubPopHead','sub_pop_head_id');
    }
}

