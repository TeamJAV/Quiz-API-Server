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
            'id' => 'nullable',
            'name' => 'required|min:1|max:25|unique:rooms',
            'status' => 'nullable',
            'is_shuffle' => 'nullable'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (Room::query()->where('user_id', auth()->id())->count() > 20) {
                $validator->errors()->add('name', 'You have reached the maximum of rooms.');
            }
//            $room = auth()->user()->rooms()->where('name', $this->name)->first();
//            if ($this->id != null) {
//                if ($room && $room['id'] != $this->id) {
//                    $validator->errors()->add('name', 'The room name has already exists.');
//                }
//            } elseif ($room) {
//                $validator->errors()->add('name', 'The room name has already exists.');
//            }
        });
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
