<?php

namespace App\Http\Requests;

use App\Repositories\userRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'sometimes|Nullable|string|max:60',
            'family'=>'sometimes|Nullable|string|max:60',
            'email'=>'sometimes|Nullable|email',         
            'user_birthday'=>'sometimes|Nullable|date',
            'user_location_id'=>'sometimes|Nullable|integer',
            'user_gender'=>'sometimes|Nullable|integer|max_digits:2',
            'user_desc'=>'sometimes|Nullable|string',
            'user_code'=>'sometimes|Nullable|unique:users,user_code'
        ];
    }
}
