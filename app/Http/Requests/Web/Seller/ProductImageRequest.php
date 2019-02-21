<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\Product;
use App\ProductImage;
use Illuminate\Support\Facades\Auth;

class ProductImageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->request->all();
        if($data['id'] == 0){
            return true;
        }else{
            try{
                $productImage = ProductImage::findOrFail($data['id']);
                $product = Product::findOrFail($productImage->product_id);
                $user = Auth::user();
                if($user->seller->id==$product->seller_id){
                    return true;
                }
            }catch(\Exception $e){
                return false;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
