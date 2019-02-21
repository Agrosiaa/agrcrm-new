<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegistrationRequest extends Request
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
            'email' => 'required|chk_email',
            'mobile' => 'required|mobile',
            'pincode' => 'required|zip',
            'company' => 'required|alpha_space_num|min:3',
            'gstin' => 'required|alpha_space_num|min:15|max:15',
            'shop_no_office_no_survey_no' => 'required|max:25',
            'name_of_premise_building_village' => 'required|min:3|max:25',
            'road_street_lane'=>'required|min:3|max:25',
            'area_locality_wadi' => 'required|alpha_space_sym|min:3|max:25',
            'at_post' => 'required',
            'taluka' => 'required',
            'district' => 'required',
            'state' => 'required',
            'password' => 'required|min:5|max:20',
            'bank_name' => 'required|alpha_spaces|min:3|max:50',
            'ifsc_code' => 'required|ifsc',
            'pan_number' => 'required|pan',
            'tan_number' => 'tan',
            'account_type' => 'required|min:5|max:255',
            'branch_name' => 'required|alpha_space_num|min:5|max:50',
            'company_identification_number' => 'alpha_num|min:5|max:50',
            'beneficiary_name' => 'required|alpha_space_num|min:5|max:50',
            'account_no' => 'required|min:9|max:16',
            'seeds_lic_number' => 'numeric',
            'fertilizers_lic_number' => 'numeric',
            'pesticides_lic_number' => 'numeric',
            'others_lic_number' => 'numeric',
            'pan_card' => 'required|mimes:jpeg,png,jpg,pdf|max:10000',
            'shop_act' => 'required|mimes:jpeg,png,jpg,pdf|max:10000',
            'gstin_certificate' => 'required|mimes:jpeg,png,jpg,pdf|max:10000',
            'cancelled_cheque' => 'required|mimes:jpeg,png,jpg,pdf|max:10000',
            'seeds_licence' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'fertilizers_licence' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'pesticides_licence' => 'mimes:jpeg,png,jpg,pdf|max:10000',
            'others_licence' => 'mimes:jpeg,png,jpg,pdf|max:10000'


        ];
    }
}
