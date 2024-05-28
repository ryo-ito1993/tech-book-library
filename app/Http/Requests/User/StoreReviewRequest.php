<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'isbn' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'string', 'url', 'max:255'],
            'authors' => ['array'],
            'authors.*' => ['string', 'max:255'],
            'review' => ['required', 'string', 'max:255', 'min:1'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'categories' => ['array', 'nullable'],
            'categories.*' => ['exists:categories,id'],
            'levelCategories' => ['array', 'nullable'],
            'levelCategories.*' => ['exists:level_categories,id'],
        ];
    }
}
