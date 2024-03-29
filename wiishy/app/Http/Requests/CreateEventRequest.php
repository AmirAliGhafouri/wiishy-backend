<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'name'=>'required|string|max:60',
            'family'=>'required|string|max:60',
            'gender'=>'required|max_digits:1',
            'relationship'=>'required|max_digits:1',
            'repeatable'=>'required|max_digits:1',
            'event_type'=>'required|max_digits:1',
            'event_date'=>'required',
        ];
    }
}
