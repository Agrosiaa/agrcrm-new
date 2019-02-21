<?php

namespace App\Http\Requests\Web\Superadmin;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class FeatureRequest extends Request
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
                    'name' => 'required|alpha_space_num|max:60',
                    'code' => 'required|alpha_space_num|unique:features|max:60',
                    'visibility' => 'required',
                    'required' => 'required',
                    'searchable' => 'required',
                    'use_in_filter' => 'required',
                    'comparable' => 'required',
                    'category_id' => 'required',
                    'input_type_id' => 'required',
                    'excel_column_description' => 'required',
                    'excel_column_input_type_description' => 'required'
                ];
            break;
            case 'PUT':
                return [
                    'required' => 'required',
                    'searchable' => 'required',
                    'use_in_filter' => 'required',
                    'comparable' => 'required',
                    'excel_column_measurable_unit_description' => 'alpha_specialchars',
                    'excel_column_measurable_unit_input_type_description' => 'alpha_specialchars',
                    'excel_column_measurable_unit_example' => 'alpha_specialchars',
                    'visibility' => 'required'
                ];
            break;
        }
    }

    public function validator($factory)
    {
        /*if($this->request->has('feature_option')){
            $required = 'required';
        }else{
            if($this->request->has('input_type_id')){
                dd($this->request->input_type_id='text');
                $required = 'required';
            }else{
                $required = '';
            }
        }*/
        $data = $this->request->all();
        if($this->request->has('input_type_id')){
            if($data['input_type_id']== 'text'){
                $required = '';
            }else{
                $required = 'required';
            }
        }else{
            $required = 'required';
        }
        if (Session::has('applocale')) {
            $language = Session::get('applocale');
            if($language == 'en'){
                $v = $factory->make($this->all(), $this->rules());
                if($this->request->has('feature_option')) {
                    $v->each('feature_option', [$required, 'alpha_space_num_specialchar', 'min:3', 'max:40']);
                }
                return $v;
            }else{
                $v = $factory->make($this->all(), $this->rules());
                if($this->request->has('feature_option')) {
                    $v->each('feature_option', [$required]);
                }
                return $v;
            }
        }

    }
}
