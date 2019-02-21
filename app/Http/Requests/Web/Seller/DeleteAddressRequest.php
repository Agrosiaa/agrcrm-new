<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\SellerAddress;
use Illuminate\Support\Facades\Auth;

class DeleteAddressRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try{
            $address = SellerAddress::findOrFail($this->route('id'));
            $user = Auth::user();
            if($address->seller_id!=$user->seller->id){
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
