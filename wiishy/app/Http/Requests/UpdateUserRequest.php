<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::debug('updateProfileRequest =>' . json_encode($this->input('name')));
        return [
            'name'=>'sometimes|Nullable|string|max:60',
            'family'=>'sometimes|Nullable|string|max:60',
            'user_birthday'=>'sometimes|Nullable|date',
            'user_location_id'=>'sometimes|Nullable|integer',
            'user_gender'=>'sometimes|Nullable|integer|max_digits:2',
            'user_desc'=>'sometimes|Nullable|string',
            'user_code'=>'sometimes|Nullable|unique:users,user_code',
            'image' => 'sometimes|image|dimensions:max_width=1024,max_height=1024|max:2048'
        ];
    }
}
