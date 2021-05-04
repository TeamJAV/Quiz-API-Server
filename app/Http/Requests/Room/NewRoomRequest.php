<?php

namespace App\Http\Requests\Room;

use App\Models\Room;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'id' => 'nullable|numeric',
            'name' => 'required|min:1|max:25|unique:rooms,name,NULL,id,deleted_at,NULL',
            'status' => 'nullable',
            'is_shuffle' => 'nullable'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (Room::query()->where('user_id', auth()->id())->count() > 20) {
                $validator->errors()->add('name', 'You have reached the maximum of rooms');
            }
        });
    }

    public function failedValidation(Validator $validator): \Illuminate\Http\JsonResponse
    {
        throw new HttpResponseException(
            response()->json(['success' => false, 'status' => 400, 'error' => $validator->errors()->first()])
        );
    }
}
