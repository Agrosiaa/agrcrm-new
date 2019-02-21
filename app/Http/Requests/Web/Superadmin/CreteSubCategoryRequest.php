<?php

namespace App\Http\Requests\Web\Superadmin;

use App\Http\Requests\Request;

class CreteSubCategoryRequest extends Request
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
                    'name' => 'required',
                    'image' => 'required|mimes:jpeg,jpg,png',


                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required',
                    'image' => 'required|mimes:jpeg,jpg,png',

                ];
                break;
        }
    }
}
