<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateRejectedBacsRequest extends Request
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
}
