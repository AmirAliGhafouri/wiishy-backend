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
            'user_name'=>'required|string|max:60',
            'user_family'=>'required|string|max:60',
            'user_birthday'=>'required|date',
            'user_location'=>'required | integer',
            'user_gender'=>'required|integer|max_digits:2',
            'user_description'=>'required|string',
            'user_code'=>'required|unique:users,userCode'
        ];
    }
}
