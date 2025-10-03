<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        $id = $this->route('product')->id ?? null;

        return [
            'name'        => ['required','string','max:255'],
            'code'        => ['required','string','max:255', Rule::unique('products','code')->ignore($id)],
            'slug'        => ['nullable','string','max:255', Rule::unique('products','slug')->ignore($id)],
            'type'        => ['nullable','string','max:255'],
            'price'       => ['required','numeric','min:0'],
            'description' => ['nullable','string'],
            'category_id' => ['required','exists:categories,id'],

            // картинки
            'images'      => ['sometimes','array'],
            'images.*'    => ['file','image','mimes:jpg,jpeg,png,webp','max:5120'], // 5MB
            'delete_images'     => ['sometimes','array'],
            'delete_images.*'   => ['integer','exists:product_images,id'],
            'primary_image_id'  => ['nullable','integer'], // может быть id существующего или 'new_*'
        ];
    }
}
