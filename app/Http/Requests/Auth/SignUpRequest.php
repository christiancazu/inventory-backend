<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Enums\Field;

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
            'names'     => 'required|string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'surnames'  => 'required|string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'email'     => 'required|email|string|min:'.Field::MIN_EMAIL.'|max:'.Field::MAX_EMAIL.'|unique:users',
            'doc_num'   => 'required|integer|between:'.Field::MIN_DOC_VAL.','.Field::MAX_DOC_VAL.'|unique:users',
            'role_id'   => 'required|exists:roles,id'
            // 'password'  => 'required|confirmed'
        ];
    }
}
