<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;

class DeleteProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try{
            $product = Product::findOrFail($this->route('id'));
            $user = Auth::user();
            if($product->seller_id!=$user->seller->id){
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
        return true;
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
