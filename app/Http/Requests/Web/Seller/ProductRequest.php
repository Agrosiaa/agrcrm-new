<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->session()->get('role_type')=='seller'){
            switch ($this->method()) {
                case 'GET':
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
                    break;
                case 'POST':
                    return true;
                    break;
            }
        }else{
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
        switch ($this->method()) {
            case 'GET':
                return [
                    //
                ];
                break;
            case 'POST':
                if (Session::has('applocale')) {
                    $language = Session::get('applocale');
                    if($language == 'en'){
                        return [
                            'product_name' => 'required|max:90',
                            'product_description' => 'required|max:255',
                            'seller_sku' => 'required|max:64',
                            'model_name' => 'required|max:255',
                            'key_specs_1' => 'required|max:100',
                            'key_specs_2' => 'required|max:100',
                            'key_specs_3' => 'max:100',
                            'weight' => 'required|float7left',
                            'weight_measuring_unit' => 'required',
                            'height' => 'required|float7left',
                            'width' => 'required|float7left',
                            'length' => 'required|float7left',
                            'packaging_dimensions_measuring_unit' => 'required',
                            'final_weight_of_packed_material' => 'required|float7left',
                            'final_weight_measuring_unit' => 'required',
                            'seller_address_id' => 'max:200',
                            'sales_package_or_accessories' => 'max:60',
                            'domestic_warranty' => 'max:60',
                            'warranty_summary' => 'max:60',
                            'warranty_service_type' => 'max:60',
                            'warranty_items_covered' => 'max:60',
                            'warranty_items_not_covered' => 'max:60',
                            'search_keywords' => 'required|max:10000',
                            'base_price_final' => 'required|float9left',
                            'selling_price_without_discount' => 'required',
                            'discounted_price' => 'required',
                            'discount_percent' => 'required|min:0|max: 100',
                            'subtotal_final' => 'required',
                            'tax_id' => 'required',
                            'quantity' => 'required|numeric',
                            'minimum_quantity' => 'required|numeric|min:1',
                            'maximum_quantity' => 'required|numeric|min:1',
                            'other_features_and_applications'=>'required'
                        ];
                        break;
                    }else{
                        return [
                            //
                        ];
                        break;
                    }
                }
        }
    }
}
