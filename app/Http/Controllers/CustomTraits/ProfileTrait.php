<?php
namespace App\Http\Controllers\CustomTraits;

use App\Seller;
use App\SellerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait ProfileTrait{

    public function checkAddressAbbreviation(Request $request){
        try {
            $abbreviation = strtolower($request['address_unique_name']);
            $sellerId = Seller::where('user_id',$request->user_id)->first();
            $abbreviationCheck = SellerAddress::where('address_unique_name',$abbreviation)->where('seller_id',$sellerId->id)->first();
            if($abbreviationCheck == null) {
                return 'true';
            } else {
                return 'false';
            }
        } catch(\Exception $e) {
            $data = [
                'input_params' => $request->all(),
                'action' => 'check abbreviation name',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}