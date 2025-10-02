<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool { return $this->user('admin') !== null; }

    public function rules(): array
    {
        $id = $this->route('category')->id ?? null;

        return [
            'name'        => ['required','string','max:255'],
            'slug'        => ['nullable','string','max:255', Rule::unique('categories','slug')->ignore($id)],
            'parent_id'   => ['nullable','different:id','exists:categories,id'],
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
