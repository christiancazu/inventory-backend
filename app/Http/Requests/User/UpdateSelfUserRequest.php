<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Enums\Field;

class UpdateSelfUserRequest extends FormRequest
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
            'names'         => 'string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'surnames'      => 'string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'email'         => 'email|string|min:'.Field::MIN_EMAIL.'|max:'.Field::MAX_EMAIL.'|unique:users,email,'.$this->user()->id, 
            'doc_num'       => 'integer|between:'.Field::MIN_DOC_VAL.','.Field::MAX_DOC_VAL.'|unique:users,doc_num,'.$this->user()->id,
            'password'      => 'string|min:'.Field::MIN_PASS_USER.'|max:'.Field::MAX_PASS_USER,
            'new_password'  => 'required_with:password|string|min:'.Field::MIN_PASS_USER.'|max:'.Field::MAX_PASS_USER,
        ];
    }
}
