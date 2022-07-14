<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorRegistRequest extends FormRequest
{
    /**
     * Determine if the operator is authorized to make this request.
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
            'email'    =>  'required|email|max:255|unique:operators',//ユニークにしたい「Rule::unique('operators')」
            'password' =>  'required',
        ];
    }
}