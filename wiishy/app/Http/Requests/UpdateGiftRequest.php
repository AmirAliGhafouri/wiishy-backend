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
            'gift_name'=>'sometimes|string|max:100',
            'gift_price'=>'sometimes|numeric',
            'gift_desc'=>'sometimes|string',
            'desire_rate'=>'sometimes|max_digits:2',
            'gift_image_url'=>'sometimes|string'
        ];
    }
}
