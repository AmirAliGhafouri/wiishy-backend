<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGiftRequest extends FormRequest
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
    public function rules():array
    {
        return [
            'gift_name'=>'required|string|max:100',
            'gift_price'=>'required|numeric',
            'gift_desc'=>'required|string',
            'desire_rate'=>'required|max_digits:2',
            'gift_image_url'=>'required|string'
        ];
    }
}
