<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = ['order_id','vat_rate','vat_name','final_amount','created_at','updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order','order_id');
    }
}
