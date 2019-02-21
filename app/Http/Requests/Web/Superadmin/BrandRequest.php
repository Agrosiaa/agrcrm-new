<?php

namespace App\Http\Requests\Web\Superadmin;

use App\Http\Requests\Request;

class BrandRequest extends Request
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
                    'name' => 'required|unique:brands',
                    'category_id' => 'required',
                ];
                break;
            case 'PUT':
                return [

                ];
                break;
        }
    }
}
