<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveSiteRequest extends Request
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
              'name'        => 'required|min:3|max:255',
              'city'        => 'required|min:3|max:255',
              'state'           => 'required|min:0|max:255',
              
              'address'         => 'required|min:5|max:80',
              'address2'        => 'required|min:2|max:80',
              'zip'         => 'required|min:3|max:10',
              'contact_person'         => 'required',
              'contact_number'         => 'required',
        
        ];
    }
     public function messages()
    {
         return [
            'name.required' => 'The Site name field is required.',
           
            'address.required' => 'The Address field is required.',
            'address2.required' => 'The Address2 field is required.',
            'city.required' => 'The City field is required.',
            'state.required' => 'The County field is required.',
            'zip.required' => 'The Post code field is required.',
            'contact_number.required' => 'The Contact Number field is required.',
            'contact_person.required' => 'The Contact Person field is required.',
            
           
           
        ];
    }
}
