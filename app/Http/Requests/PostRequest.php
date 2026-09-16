<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'category_id' => 'required|exists:categories,id',

            'condition' => 'required|string',
            'province_id' => 'required|exists:iran_provinces,id',
            'city_id' => ['required', Rule::exists('iran_cities', 'id')->where(fn ($query) => $query->where('province_id', $this->input('province_id')))],

            'first_day_price' => 'nullable|numeric|min:0',
            'extra_day_price' => 'nullable|numeric|min:0',

            'available_from' => 'nullable|date',
            'available_untill' => 'nullable|date|after_or_equal:available_from',
        ];

        if($this->isMethod('post')){
            $rules['images'] = 'required|array|max:5';
            $rules['images.*'] = 'image|max:5120';
        }
        return $rules;
    }
}
