<?php

namespace App\Http\Requests\Web\Seller;

use App\Http\Requests\Request;

class ExcelRequest extends Request
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
            'excel_file' => 'required|mimes:xls,xlsx|max:2000'
        ];
    }
}
