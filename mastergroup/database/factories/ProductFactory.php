<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // берём любую существующую категорию или создаём корневую
        $category = Category::inRandomOrder()->first() ?? Category::create([
            'name' => 'General',
            'slug' => 'general',
            'is_active' => true,
        ]);

        $name = $this->faker->words(rand(2, 4), true);
        $code = strtoupper('PRD-' . Str::random(3) . '-' . $this->faker->numerify('###'));

        return [
            'category_id' => $category->id,
            'name'        => Str::title($name),
            'code'        => $code,
            'slug'        => null, // сгенерируется в модельном хукe
            'type'        => $this->faker->randomElement(['standard', 'premium', 'limited']),
            'description' => $this->faker->sentences(rand(2, 5), true),
            'price'       => $this->faker->numberBetween(100, 900),

        ];
    }
}
