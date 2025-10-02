<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return $this->user('admin') !== null; }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'slug'        => ['nullable','string','max:255','unique:categories,slug'],
            'parent_id'   => ['nullable','exists:categories,id'],
            'is_active'   => ['sometimes','boolean'],
            'description' => ['nullable','string'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => (bool) $this->boolean('is_active'),
        ]);
    }
}
