<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProductQueryStatusRequest extends Request
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
            $user = User::where('id',Auth::user()->id)->with('seller')->first()->toArray();
            $customArray = ['id'=>$data['product_id'],'seller_id'=>$user['seller']['id']];
            $product = Product::where($customArray)->get();
            if(!$product->isEmpty()){
                return true;
            }else{
                return false;
            }
        }elseif($this->session()->get('role_type')=='superadmin'){
                return true;
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
        if($this->session()->get('role_type')=='seller'){
            return [
                'conversation' => 'required|max:500',
                'product_id' => 'required'
            ];
        }else{
            return [
                //
            ];
        }
    }
}
