<?php

namespace App\Http\Requests\Web\Superadmin\vendor;

use App\Http\Requests\Request;

class AddNewAddressRequest extends Request
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
            'address_unique_name' => 'required|alpha_num_hyphen|min:10|max:20',
            'at_post' => 'required',
            'taluka' => 'required',
            'district' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'name_of_premise_building_village' => 'required|min:1|max:25',
            'shop_no_office_no_survey_no' => 'required|min:1|max:25',
            'area_locality_wadi' => 'required|min:1|max:25',
            'road_street_lane' => 'required|min:1|max:25'
        ];
    }
}
