<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'names'     => 'required',
            'surnames'  => 'required',
            'email'     => 'required|email|unique:users',
            'doc_num'   => 'required|unique:users|string|min:100000|max:999999999999|integer',
            'password'  => 'required|confirmed'
        ];
    }
}
