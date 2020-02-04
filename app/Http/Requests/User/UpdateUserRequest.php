<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use App\Enums\Field;

class UpdateUserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        // $request->user represents to /{user} as slug id
        return [
            'names'         => 'string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'surnames'      => 'string|min:'.Field::MIN_STRING.'|max:'.Field::MAX_NAME_USER,
            'email'         => 'email|string|min:'.Field::MIN_EMAIL.'|max:'.Field::MAX_EMAIL.'|unique:users,email,'.$request->user,
            'doc_num'       => 'integer|between:'.Field::MIN_DOC_VAL.','.Field::MAX_DOC_VAL.'|unique:users,doc_num,'.$request->user,
            'activated'     => 'boolean',
            'role_id'       => 'exists:roles,id'
        ];
    }
}
