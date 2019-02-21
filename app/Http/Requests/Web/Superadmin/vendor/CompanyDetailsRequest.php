<?php

namespace App\Http\Requests\Web\Superadmin\vendor;

use App\Http\Requests\Request;

class CompanyDetailsRequest extends Request
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
            'company_identification_number' => 'alpha_num|min:5|max:50',
            'company' => 'required|alpha_spaces',
            'gstin' => 'required|alpha_num|min:15|max:15'
        ];
    }
}
