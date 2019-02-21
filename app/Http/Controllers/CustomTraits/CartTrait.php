<?php
namespace App\Http\Controllers\CustomTraits;


use App\Cart;
use Illuminate\Support\Facades\Log;

trait CartTrait{

    public function totalPrice($cartIds){
        try{
            $cartItems = Cart::whereIn('id',$cartIds)->where('is_purchased',0)->get();
            if($cartItems==NULL){
                $price = 0;
            }else{
                $price = $cartItems->sum('discounted_price');
            }
        }catch (\Exception $e){
            $data = [
                'action' => 'check cart total price',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            $price = 0;
        }
        return $price;

    }


}