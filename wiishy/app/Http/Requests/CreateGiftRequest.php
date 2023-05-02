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
            'g_name'=>'required | max:100',
            'g_price'=>'required | numeric',
            'g_desc'=>'required',
            'g_rate'=>'required',
            'g_image'=>'required'
        ];
    }
}
