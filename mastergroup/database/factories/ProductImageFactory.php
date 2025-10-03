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
        $w = 800; $h = 600;
        return [
            'product_id' => Product::factory(),
            'path'       => "https://picsum.photos/seed/".$this->faker->uuid."/$w/$h",
            'alt'        => $this->faker->words(3, true),
            'sort_order' => 0,
            'is_primary' => false,
        ];
    }
}
