<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;

class BankDetailsRequest extends Request
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
            'bank_name' => 'required|alpha_spaces|min:3|max:50',
            'ifsc_code' => 'required|ifsc',
            'pan_number' => 'required|pan',
            'tan_number' => 'tan',
            'account_type' => 'required|min:5|max:255',
            'branch_name' => 'required|alpha_space_num|min:5|max:50',
            'company_identification_number' => 'required|alpha_space_num|min:5|max:50',
            'beneficiary_name' => 'required|alpha_space_num|min:5|max:50',
            'account_no' => 'required|numeric|min:14|max:16'
        ];
    }
}
