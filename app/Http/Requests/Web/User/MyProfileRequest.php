<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\Request;

class MyProfileRequest extends Request
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
     * Get the js rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()){
            case 'POST':
                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' =>'chk_email',
                ];
                break;
            case 'PUT':break;

        }
    }
}
