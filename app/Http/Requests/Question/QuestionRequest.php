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
            'question_type'=>'required|in:multiple,true-false,short-answer'
        ];
    }

    public function failedValidation(Validator $validator): \Illuminate\Http\JsonResponse
    {
        throw new HttpResponseException(
            response()->json(['success' => false, 'status' => 400, 'error' => $validator->errors()->first()])
        );
    }
}
