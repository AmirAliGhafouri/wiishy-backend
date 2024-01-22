<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:60',
            // 'family'=>'required|string|max:60',
            'email'=>'required|string|email|max:150',
            // 'user_birthday'=>'required|date',
            // 'user_location_id'=>'required | integer',
            // 'user_gender'=>'required|integer|max_digits:2',
            // 'producer'=>'sometimes|Nullable|integer|max_digits:2',
            // 'user_desc'=>'required|string',
            // 'user_code'=>'required|unique:users,user_code'
        ];
    }
}
