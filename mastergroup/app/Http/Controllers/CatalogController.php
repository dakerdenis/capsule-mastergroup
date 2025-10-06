<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // ← добавь
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // ---- входные параметры
        $q       = trim((string) $request->get('q'));
        $catId   = $request->integer('category_id');
        $type    = trim((string) $request->get('type'));
        $sort    = $request->get('sort'); // new, old, price_asc, price_desc
        $sort    = in_array($sort, ['new','old','price_asc','price_desc'], true) ? $sort : 'new';

        // ---- базовый запрос
        $query = Product::query()
            ->with([
                'primaryImage',
                'images'    => fn($q2) => $q2->orderBy('sort_order'),
                'category:id,name',
            ]);

        // ---- поиск по имени/коду/slug
        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('name','like',"%{$q}%")
                  ->orWhere('code','like',"%{$q}%")
                  ->orWhere('slug','like',"%{$q}%");
            });
        }

        // ---- фильтры
        if ($catId)      $query->where('category_id', $catId);
        if ($type !== '') $query->where('type', $type);

        // ---- сортировка
        match ($sort) {
            'old'        => $query->orderBy('id'),            // от старых к новым
            'price_asc'  => $query->orderBy('price')->orderByDesc('id'),
            'price_desc' => $query->orderByDesc('price')->orderByDesc('id'),
            default      => $query->orderByDesc('id'),        // new: от новых к старым
        };

        $products = $query->paginate(10)->appends($request->query());

        $categories = Category::orderBy('name')->get(['id','name']);
        $types      = Product::query()->select('type')->distinct()->orderBy('type')->pluck('type')->filter()->values();

        return view('catalog.index', [
            'title'      => 'Catalogue',
            'products'   => $products,
            'categories' => $categories,
            'types'      => $types,
            'q'          => $q,
            'catId'      => $catId,
            'type'       => $type,
            'sort'       => $sort,
        ]);
    }

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
