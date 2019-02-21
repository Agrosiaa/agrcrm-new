<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProductQueryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->session()->get('role_type')=='seller'){
            $data = $this->request->all();
            $seller = Seller::where('user_id',Auth::user()->id)->first();
            $sellerData = ['id'=>$data['id'],'seller_id'=>$seller->id,];
            $product = Product::where($sellerData)->get();
            if($product->isEmpty()){
                return false;
            }else{
                return true;
            }
        }elseif($this->session()->get('role_type')=='admin'){
            $data = $this->request->all();
            $sellerData = ['id'=>$data['id']];
            $product = Product::where($sellerData)->get();
            if($product->isEmpty()){
                return false;
            }else{
                return true;
            }
        }
        elseif($this->session()->get('role_type')=='superadmin'){
            $data = $this->request->all();
            $sellerData = ['id'=>$data['id']];
            $product = Product::where($sellerData)->get();
            if($product->isEmpty()){
                return false;
            }else{
                return true;
            }
        }
        else{
            return true;
        }
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
