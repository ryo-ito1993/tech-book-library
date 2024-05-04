<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryRequest extends FormRequest
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
            'systemid' => ['required', 'string', 'max:255'],
            'systemname' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'systemid.required' => '図書館を選択してください。',
            'systemname.required' => '図書館を選択してください。',
        ];
    }
}
