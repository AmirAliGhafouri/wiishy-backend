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
            'price_unit_id'=>'required|numeric',
            'gift_desc'=>'required|string',
            'desire_rate'=>'required|max_digits:2',
            //'image' => 'required|image|dimensions:max_width=1000,max_height=1000|max:2048',
            'image' => $this->input('gift_image_url') ? 'nullable' : 'required|image|dimensions:max_width=6000,max_height=6000|max:2048', 
            'gift_image_url' => 'sometimes|url',
        ];
    }
}
