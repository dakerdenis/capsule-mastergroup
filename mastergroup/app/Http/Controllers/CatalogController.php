<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with([
                'primaryImage',
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name',
            ])
            ->orderByDesc('id')
            ->paginate(24);

        return view('catalog.index', [
            'title'    => 'Catalogue',
            'products' => $products,
        ]);
    }

    // NEW: JSON для попапа
    public function showJson(Product $product)
    {
        $product->load([
            'category:id,name',
            'images' => fn($q) => $q->orderBy('sort_order'),
            'primaryImage',
        ]);

        $images = $product->images->map(function ($img) {
            $url = Str::startsWith($img->path, ['http://','https://'])
                ? $img->path
                : asset('storage/' . ltrim($img->path, '/'));
            return [
                'id'         => $img->id,
                'url'        => $url,
                'is_primary' => (bool)$img->is_primary,
            ];
        })->values();

        $primary = $images->firstWhere('is_primary', true) ?? $images->first();

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'code'        => $product->code,
            'type'        => $product->type,
            'price'       => (float)$product->price,
            'description' => (string)($product->description ?? ''),
            'slug'        => $product->slug,
            'category'    => optional($product->category)->name,
            'images'      => $images,
            'primary'     => $primary,
        ]);
    }
}
