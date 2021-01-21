<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AssignmentRequest extends Request
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
            'employee'              => 'required',
            //'rate'              => 'regex:/^\d*(\.\d{1,2})?$/',
            'companies_id'              => 'required',
            'project_id'              => 'required',
            'start_date'              => 'required',
            'end_date'              => 'required',
           
            
        ];
    }
    public function messages()
    {
        return [
            'employee.required' => 'The Worker field is required.',
            //'rate.required' => 'The Rate field is required.',
            'companies_id.required' => 'The Client field is required.',
            'project_id.required' => 'The Project field is required.',
            'start_date.required' => 'The Start Date field is required.',
            'end_date.required' => 'The End Date field is required.',
           
           
        ];
    }
}
