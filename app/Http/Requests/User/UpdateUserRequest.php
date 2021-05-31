<?php

namespace App\Http\Requests\User;

use App\Rules\MatchOldPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    public function rules()
    {
        return [
            //
            'name' => 'required|min:1|max:20|unique:users,id',
            'o_password' => ['required', new MatchOldPassword()],
            'n_password' => 'required|min:10|max:50|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'c_password' => 'required|same:n_password',
        ];
    }

    public function messages()
    {
        return [
            'n_password.regex' => 'The new password must has one lowercase letter, one uppercase letter, a digit, special chars and at least 10 characters long.',
            'n_password.required' => 'The new password is required.',
            'n_password.min' => 'The new password is must be at least 10 characters.',
            'c_password.required' => 'The confirmation password is required.',
            'c_password.same' => 'The confirmation password is not match with new password.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 422,
            'success' => false,
            'message' => $validator->errors()->first(),
            'data' => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
