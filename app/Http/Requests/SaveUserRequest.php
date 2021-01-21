<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveUserRequest extends Request
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
            'first_name'              => 'required|string|min:1',
            'last_name'              => 'required|string|min:1',
            'email'                   => 'email',
            'pin_code'                   => 'required',
          //  'user_skill'                   => 'required',
            'phone'                   => 'required|numeric',
           // 'password'                => 'required|min:6',
           // 'password_confirm'        => 'sometimes|required_with:password',
            //'username'                => 'required|string|min:2|unique:users,username,NULL,deleted_at',
        
        ];
    }
     public function messages()
    {
        return [
            'first_name.required' => 'The First Name field is required.',
            'last_name.required' => 'The Last Name field is required.',
           // 'email.required' => 'The Email field is required.',
            'pin_code.required' => 'The Post Code field is required.',
            'user_skill.required' => 'The Trade field is required.',
            'phone.required' => 'The Contact number field is required.',
           
           
        ];
    }
}
