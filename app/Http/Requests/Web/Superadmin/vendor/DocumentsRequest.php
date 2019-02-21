<?php

namespace App\Http\Requests\Web\Superadmin\vendor;

use App\Http\Requests\Request;

class DocumentsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->session()->get('role_type')=='superadmin'){
            return true;
        }else{
            return false;
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
            'pan_card' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'shop_act' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'vat_certificate' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'cancelled_cheque' => 'mimes:jpeg,png,jpg,pdf|max:10000',
        ];
    }
}
