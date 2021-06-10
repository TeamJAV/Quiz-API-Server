<?php

namespace App\Http\Requests\Room;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LaunchRoomRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            //
            'id' => 'required|numeric',
            'id_quiz' => 'required|numeric',
            'time_offline' => 'nullable|numeric|gte:1|lte:120',
            'shuffle_answer' => 'required|boolean',
            'shuffle_question' => 'required|boolean'
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
