<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'name'=>'sometimes|string|max:60',
            'family'=>'sometimes|string|max:60',
            'gender'=>'sometimes|max_digits:1',
            'relationship'=>'sometimes|max_digits:1',
            'repeatable'=>'sometimes|max_digits:1',
            'event_type'=>'sometimes|max_digits:1',
            'event_date'=>'sometimes',
        ];
    }
}
