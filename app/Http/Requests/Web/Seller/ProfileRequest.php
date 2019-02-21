<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;

class ProfileRequest extends Request
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
            'first_name' => 'required|alpha|min:3|max:15',
            'last_name' => 'required|alpha|min:3|max:15',
            'mobile' => 'required|max:10',
            'pincode' => 'required|zip'
        ];
    }
}
