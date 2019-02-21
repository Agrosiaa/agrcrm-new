<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryHSNCodeTaxRelation extends Model
{
    protected $table = 'category_hsn_code_tax_relation';

    protected $fillable = [
        'hsn_code_tax_relation_id','category_id'
    ];
}
