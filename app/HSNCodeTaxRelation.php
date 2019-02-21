<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HSNCodeTaxRelation extends Model
{
    protected $table = 'hsn_code_tax_relation';

    protected $fillable = [
        'hsn_code_id','tax_id','created_at','updated_at'
    ];

    public function tax(){
        return $this->belongsTo('App\Tax' ,'tax_id');
    }

    public function hsnCode(){
        return $this->belongsTo('App\HSNCodes' , 'hsn_code_id');
    }
}
