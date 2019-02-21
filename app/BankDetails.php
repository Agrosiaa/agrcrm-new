<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $table = 'seller_bank_details';

    protected $fillable = [
    'account_no','beneficiary_name','bank_name','branch_name','company_identification_number','ifsc_code',
        'pan_number','tan_number','account_type','seller_id','created_at','updated_at'
    ];


    public function seller(){
        return $this->belongsTo('App\Seller','seller_id');
    }
}
