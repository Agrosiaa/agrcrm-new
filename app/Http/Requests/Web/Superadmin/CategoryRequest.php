<?php

namespace App\Http\Requests\Web\Superadmin;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CategoryRequest extends Request
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
                    'name' => 'required|max:90',
                    'image' => 'required|mimes:jpeg,jpg,png',
                    'return_period' => 'required|numeric',
                    'tab_name' => 'required|max:31',

                ];
                break;
            case 'PUT':
                if (Session::has('applocale')) {
                    $language = Session::get('applocale');
                    if($language == 'en'){
                        return [
                            'name' => 'required|max:90',
                        ];
                    }else {
                        $name = 'name_'.$language;
                        return [
                            $name => 'required|max:90',
                        ];
                    }
                }
                break;
        }
    }
}
