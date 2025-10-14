<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check(); // доступ только админам
    }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'code'        => ['nullable','string','max:100'],
            'slug'        => ['nullable','string','max:255','unique:products,slug'],
            'type'        => ['nullable','string','max:50'],
            'description' => ['nullable','string','max:5000'],
            'price'       => ['required','numeric','min:0'],
            'category_id' => ['nullable','exists:categories,id'],

            // изображения (опционально), максимум 5
            'images'      => ['sometimes','array','max:5'],
            'images.*'    => ['file','mimetypes:image/jpeg,image/png,image/webp','max:5120'], // <=5MB

            // можно передать id, который сделать primary (не обязательно)
            'primary_image_id' => ['nullable','integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Slug is already taken. Leave it empty to auto-generate.',
            'images.max'  => 'You can upload up to 5 images.',
        ];
    }
}
