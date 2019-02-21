<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\Request;

class OrderRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'customer_address_id' => 'required',
            'delivery_type_id' => 'required',
            'cart_items' => 'required',
            'payment_method_id' => 'required',
        ];
    }
}
