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
            'gift_name'=>'sometimes|Nullable|string|max:100',
            'gift_price'=>'sometimes|Nullable|numeric',
            'gift_desc'=>'sometimes|Nullable|string',
            'gift_url'=>'sometimes|Nullable|string',
            'desire_rate'=>'sometimes|Nullable|max_digits:2',
            'image' => 'required|image|max:2048'
        ];
    }
}
