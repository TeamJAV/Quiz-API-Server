<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignupRequest extends FormRequest
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
            'name' => 'required|min:1|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:10|max:50|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'c_password' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'The password must has one lowercase letter, one uppercase letter, a digit, special chars and at least 10 characters long.'
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
