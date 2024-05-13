<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'category_id' => 'required|int',
            'level_id' => 'required|int',
            'isGraduate' => 'required',
            'choices' => 'required|array|min:3|max:3',
            'choices.*.name' => 'required|string',
            'choices.*.is_correct' => 'required',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
