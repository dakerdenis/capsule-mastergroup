<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        // простой плейсхолдер (можно заменить позже на реальные пути загрузки)
        $w = 800; $h = 600;
        $placeholder = "https://picsum.photos/seed/".$this->faker->uuid."/$w/$h";

        return [
            'product_id' => Product::factory(), // по умолчанию создаст продукт
            'path'       => $placeholder,
            'alt'        => $this->faker->words(3, true),
            'sort_order' => 0,
            'is_primary' => false,
        ];
    }
}
