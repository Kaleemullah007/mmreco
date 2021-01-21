<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveRejectedBacsRequest extends Request
{
    /**
     * Determine if the rejected bank statement is authorized to make this request.
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
            'Date'  => 'required',
            'Token' => 'required',
            'Txn_Amt'   => 'required|numeric',
        ];
    }
     public function messages()
    {
        return [
            // 'Date.required' => 'The Date field is required.',
            // 'Token.required' => 'The Token field is required.',           
            // 'Txn_Amt.required' => 'The Transaction amount field is required.',           
        ];
    }
}
