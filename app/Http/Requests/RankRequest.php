<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'point' => 'required|min:0',
            'category_id' => 'required|int',
            'level_id' => 'required|int',
            'questions' =>'required|array'
        ];
    }
}