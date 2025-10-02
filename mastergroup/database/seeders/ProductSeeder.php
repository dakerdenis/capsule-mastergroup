<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // создадим 20 продуктов, каждому 1–5 фото, первое — основным
        Product::factory()
            ->count(20)
            ->create()
            ->each(function (Product $p) {
                $count = rand(1,5);
                for ($i=0; $i<$count; $i++) {
                    ProductImage::factory()->create([
                        'product_id' => $p->id,
                        'sort_order' => $i,
                        'is_primary' => $i === 0,
                    ]);
                }
            });
    }
}
