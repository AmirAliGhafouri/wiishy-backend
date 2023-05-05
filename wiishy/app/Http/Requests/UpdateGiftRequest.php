<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGiftRequest extends FormRequest
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
            'g_name'=>'sometimes|Nullable|string|max:100',
            'g_price'=>'sometimes|Nullable|numeric',
            'g_desc'=>'sometimes|Nullable|string',
            'g_rate'=>'sometimes|Nullable|max_digits:2',
            'g_image'=>'sometimes|Nullable|string'
        ];
    }
}
