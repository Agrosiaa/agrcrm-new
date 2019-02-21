<?php
namespace App\Http\Controllers\CustomTraits;


use App\SellerCategoryCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait ProductSku{

    public function product_category_count($seller_id,$category_id){
        try{
            $currentTime = Carbon::now();
           $sellerCount =  SellerCategoryCount::where('seller_id',$seller_id)->where('category_id',$category_id)->first();
            if($sellerCount == null){

                $sellerCategoryData['seller_id'] = $seller_id;
                $sellerCategoryData['category_id'] = $category_id;
                $sellerCategoryData['count'] = 1;
                $sellerCategoryData['created_at'] = $currentTime;
                $sellerCategoryData['updated_at'] = $currentTime;
                $createdProduct = SellerCategoryCount::insert($sellerCategoryData);
                $sellerCount =  SellerCategoryCount::where('seller_id',$seller_id)->where('category_id',$category_id)->first();
                return $sellerCount;
            }
            else{

                $sellerCount =  SellerCategoryCount::where('seller_id',$seller_id)->where('category_id',$category_id)->first();
                $sellerCategoryData['count'] = $sellerCount->count + 1;
                $createdProduct = SellerCategoryCount::where('seller_id',$seller_id)->where('category_id',$category_id)->update($sellerCategoryData);
                $sellerCount =  SellerCategoryCount::where('seller_id',$seller_id)->where('category_id',$category_id)->first();
                return $sellerCount;
            }

        }catch(\Exception $e){
            $data = [
                'seller name' => $seller_id,
                'category id' => $category_id,
                'action' => 'get itembased sku',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }


}