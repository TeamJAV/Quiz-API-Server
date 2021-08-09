<?php

namespace App\Http\Requests\Question;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionRequest extends FormRequest
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
            'title' => 'required',
            'explain'=>'nullable',
            'choices'=>'required',
            'correct_choice'=>'nullable',
//            'img'=>'nullable|mimes:jpeg,bmp,png',
            'question_type'=>'required|in:multiple,true-false,short-answer'
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
