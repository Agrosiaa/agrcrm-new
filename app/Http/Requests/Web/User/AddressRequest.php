<?php

namespace App\Http\Requests\Web\User;

use App\CustomerAddress;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class AddressRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->request->all();
        switch($this->method()) {
            case 'POST':
                if(Auth::guest()){
                    return false;
                }
               return true;
                break;
            case 'DELETE':
                try{
                    $address = CustomerAddress::findOrFail($data['id']);
                    $user = Auth::user();
                    if($address->customer_id!=$user->customer->id){
                        return false;
                    }
                }catch (\Exception $e){
                    return false;
                }
                return true;
                break;
            case 'PUT':
                try{
                    $address = CustomerAddress::findOrFail($data['edit_address_id']);
                    $user = Auth::user();
                    if($address->customer_id!=$user->customer->id){
                        return false;
                    }
                }catch (\Exception $e){
                    return false;
                }
                return true;
                break;
        }
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
                    'full_name' => 'required|min:1|max:25',
                    'mobile' => 'required|min:1|max:25',
                    'flat_door_block_house_no' => 'min:1|max:25',
                    'name_of_premise_building_village' => 'min:1|max:25',
                    'area_locality_wadi' => 'min:1|max:25',
                    'road_street_lane' => 'min:1|max:25',
                    'taluka' => 'required|min:1|max:25',
                    'district' => 'required|min:1|max:25',
                    'pincode' => 'required|min:1|max:25',
                    'state' => 'required|min:1|max:25',
                    'at_post' => 'required|min:1|max:100',
                ];
                break;
            case 'PUT':
                return [
                    'full_name' => 'required|min:1|max:25',
                    'mobile' => 'required|min:1|max:25',
                    'flat_door_block_house_no' => 'min:1|max:25',
                    'name_of_premise_building_village' => 'min:1|max:25',
                    'area_locality_wadi' => 'min:1|max:25',
                    'road_street_lane' => 'min:1|max:25',
                    'taluka' => 'required|min:1|max:25',
                    'district' => 'required|min:1|max:25',
                    'pincode' => 'required|min:1|max:25',
                    'state' => 'required|min:1|max:25',
                    'at_post' => 'required|min:1|max:100',
                    'edit_address_id'=>'required|min:1|max:25'
                ];
                break;
            case 'DELETE':
                return [

                ];
                break;
        }
    }
}
