<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ImportBankStatementRequest extends Request
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
          'user_import_csv.*' => 'required|mimes:csv,xls,xlsx|max:2000',
          'bank_master_id' => 'required',
        ];
    }

    public function response(array $errors)
    {
        return $this->redirector->back()->withInput()->withErrors($errors, $this->errorBag);
    }
}
