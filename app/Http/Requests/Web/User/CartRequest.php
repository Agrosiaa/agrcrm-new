<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\Request;

class CartRequest extends Request
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
        switch($this->method()){
            case 'POST':
                return [
                    'product_id' => 'required',
                    'buy_type' => 'required',
                ];
                break;
            case 'PUT':
                return [
                    'id' => 'required',
                ];
                break;
        }
    }
}
