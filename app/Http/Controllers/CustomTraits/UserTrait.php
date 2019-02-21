<?php
namespace App\Http\Controllers\CustomTraits;


use App\Coupon;
use App\Seller;
use App\SellerAddress;
use App\UsedCoupon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

trait UserTrait{

    public function getProductImagePathUser($imageName,$productOwnerId){
        try{
            $ds = DIRECTORY_SEPARATOR;
            $sellerUploadConfig = env('SELLER_FILE_UPLOAD');
            $sha1UserId = sha1($productOwnerId);
            $sellerUploadPath = public_path().$sellerUploadConfig;
            $sellerImageUploadPath = $sellerUploadPath.$sha1UserId.$ds.'product_images'.$ds.$imageName;
            /* Check file exists or not Directory If Not Exists */
            $file['status'] = false;
            if (file_exists($sellerImageUploadPath)) {
                $file['status'] = true;
            }
            $path = $sellerUploadConfig.$sha1UserId.$ds.'product_images'.$ds.$imageName;
            $file['path'] = $path;
            return $file;
        }catch(\Exception $e){
            $data = [
                'image name' => $imageName,
                'product owner id' => $productOwnerId,
                'action' => 'user side get image path',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function firstFiveThousand($customer,$price){
        try{
            $discount = 0;
            $coupon = Coupon::where('code','first5000')->first();
            $data = ['coupon_id'=>$coupon->id,'customer_id'=>$customer];
            $usedCoupon = UsedCoupon::where($data)->count();
            /* Coupon Code Logic [START] Remove It After Expiration Date*/
            if($customer <= 5000 && $price >= 1000 && $coupon->active && $usedCoupon==0) { // Check If Customer is Under 5000 Count
                $discount = $coupon->price;
            }
            /* Coupon Code Logic [END] */
        }catch (\Exception $e){
            $data = [
                'action' => 'Five Thousand Coupon Trait Error',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            $discount = 0;
        }
        return $discount;
    }

    public function getSellerDefaultAddress(Request $request){
        try{
            $status = 200;
            $seller_id = $request['seller_id'];
            $defaultAddressDetails = SellerAddress::where('address_unique_name','default')->where('seller_id',$seller_id)->first();
        }catch (\Exception $e){
            $status = 500;
            $data = [
                'action' => 'Get Seller Default Address',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
        }
        return response()->json($defaultAddressDetails,$status);

    }
}