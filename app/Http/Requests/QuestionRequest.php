<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|lowercase',
            'category_id' => 'required|integer',
            'level_id' => 'required|integer',
            'is_graduate' => 'required|integer',
            'choices' => 'required|array|min:3|max:3',
            'choices.*.name' => 'required|string|lowercase',
            'choices.*.is_correct' => 'required',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
