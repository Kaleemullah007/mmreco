<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveProjectRequest extends Request
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
            'project_name'              => 'required|string|min:1',
            'companies_id'              => 'required',
            'job_number'              => 'required',
            'location_ids'              => 'required',
            'project_start_date'              => 'required',
            'project_end_date'              => 'required',
            'project_value' => 'required|numeric',
           
            'project_manager' => 'required',
           
           // 'workcompleted' => 'numeric',
            
        ];
    }
    public function messages()
    {
        return [
            'location_ids.required' => 'The Site field is required.',
            'project_name.required' => 'The Project Name field is required.',
            'companies_id.required' => 'The Client field is required.',
            'job_number.required' => 'The Job Number field is required.',
            'project_start_date.required' => 'The Project Start Date field is required.',
            'project_end_date.required' => 'The Project End Date field is required.',
            'project_value.required' => 'The Site field is required.',
            'project_manager.required' => 'The Account Manager field is required.',
           
        ];
    }
}
