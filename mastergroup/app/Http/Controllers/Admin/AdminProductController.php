<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\Admin\StoreProductRequest; // <-- добавь
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string)$request->get('q'));
        $catId    = $request->integer('category_id');
        $type     = trim((string)$request->get('type'));
        $sort     = in_array($request->get('sort'), ['new', 'price_asc', 'price_desc']) ? $request->get('sort') : 'new';
        $perPage  = (int)($request->get('per_page', 20));
        $perPage  = $perPage > 0 && $perPage <= 100 ? $perPage : 20;

        $query = Product::query()
            ->with([
                'category:id,name',
                'primaryImage',                  // ← добавили
                'images' => function ($q) {      // оставили для фоллбэка и кода/счётчика
                    $q->orderBy('sort_order');
                },
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        if ($catId) $query->where('category_id', $catId);
        if ($type !== '') $query->where('type', $type);

        match ($sort) {
            'price_asc'  => $query->orderBy('price')->orderByDesc('id'),
            'price_desc' => $query->orderByDesc('price')->orderByDesc('id'),
            default      => $query->orderByDesc('id'),
        };

        $products   = $query->paginate($perPage)->appends($request->query());
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $types      = Product::query()->select('type')->distinct()->orderBy('type')->pluck('type')->filter()->values();

        return view('admin.products.index', compact('products', 'categories', 'types'))
            ->with([
                'title'   => 'Products',
                'q'       => $q,
                'catId'   => $catId,
                'type'    => $type,
                'sort'    => $sort,
                'perPage' => $perPage,
            ]);
    }

    public function edit(Product $product)
    {
        $product->load(['images' => fn($q) => $q->orderBy('sort_order')]);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $types      = Product::query()->select('type')->distinct()->orderBy('type')->pluck('type')->filter()->values();

        return view('admin.products.edit_product', [
            'title'      => 'Edit product',
            'product'    => $product,
            'categories' => $categories,
            'types'      => $types,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // проверим лимит фото: существующие - удаляемые + новые ≤ 5 и итог ≥ 1
        $existingCount = $product->images()->count();
        $toDeleteCount = count($validated['delete_images'] ?? []);
        $newCount      = isset($validated['images']) ? count($validated['images']) : 0;
        $finalCount    = $existingCount - $toDeleteCount + $newCount;

        if ($finalCount < 1 || $finalCount > 5) {
            return back()->withInput()->withErrors([
                'images' => "Total images must be between 1 and 5. Result would be: $finalCount",
            ]);
        }

        DB::transaction(function () use ($product, $validated, $request) {
            // 1) Обновить поля товара
            $product->fill([
                'name'        => $validated['name'],
                'code'        => $validated['code'],
                'slug'        => $validated['slug'] ?? null, // модель сгенерит если пусто
                'type'        => $validated['type'] ?? null,
                'description' => $validated['description'] ?? null,
                'price'       => $validated['price'],
                'category_id' => $validated['category_id'],
            ])->save();

            // 2) Удалить отмеченные изображения
            if (!empty($validated['delete_images'])) {
                $imgs = $product->images()->whereIn('id', $validated['delete_images'])->get();
                foreach ($imgs as $img) {
                    // если путь локальный — удалим
                    if (!Str::startsWith($img->path, ['http://', 'https://'])) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }

            // 3) Загрузить новые изображения (добавим в конец)
            if ($request->hasFile('images')) {
                $startOrder = (int) $product->images()->max('sort_order') + 1;
                foreach ($request->file('images') as $i => $file) {
                    $stored = $file->store("products/{$product->id}", 'public');
                    $product->images()->create([
                        'path'       => $stored,
                        'alt'        => $product->name,
                        'sort_order' => $startOrder + $i,
                        'is_primary' => false,
                    ]);
                }
            }

            // 4) Обновить primary
            $primary = $validated['primary_image_id'] ?? null;
            if ($primary) {
                // сбросить все
                $product->images()->update(['is_primary' => false]);

                if (str_starts_with((string)$primary, 'new_')) {
                    // если бы мы помечали новые как new_*, можно было бы обработать.
                    // В этой версии все новые уже сохранены, выбираем последнее главное из базы.
                    $first = $product->images()->orderByDesc('id')->first();
                    if ($first) $first->update(['is_primary' => true]);
                } else {
                    $img = $product->images()->where('id', (int)$primary)->first();
                    if ($img) $img->update(['is_primary' => true]);
                }
            } else {
                // если ничего не отмечено — следим, чтобы одно было главным
                if (!$product->images()->where('is_primary', true)->exists()) {
                    $first = $product->images()->orderBy('sort_order')->first();
                    if ($first) $first->update(['is_primary' => true]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Удалим физические файлы, если они локальные
        $product->load('images');
        foreach ($product->images as $img) {
            if ($img->path && !Str::startsWith($img->path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($img->path);
            }
        }

        // В БД у product_images стоит cascadeOnDelete — записи сотрутся вместе с продуктом
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted');
    }

      public function create()
    {
        $categories = Category::orderBy('name')->get(['id','name']);
        $types      = Product::query()
                        ->select('type')->distinct()->orderBy('type')
                        ->pluck('type')->filter()->values(); // уже имеющиеся типы
        // или задай предустановленные типы вручную, если нужно:
        // $types = collect(['physical','service','digital']);

        return view('admin.products.create', [
            'title'      => 'Create product',
            'categories' => $categories,
            'types'      => $types,
        ]);
    }

    public function store(StoreProductRequest $request): \Illuminate\Http\RedirectResponse
    {
        $v = $request->validated();

        // Сгенерим slug, если пуст
        if (empty($v['slug'])) {
            $v['slug'] = Str::slug($v['name']);
        }

        // На всякий случай уникализируем slug, если занят
        $base = $v['slug'];
        $i = 1;
        while (Product::where('slug', $v['slug'])->exists()) {
            $v['slug'] = $base . '-' . ++$i;
        }

        // Создаём продукт
        $product = DB::transaction(function () use ($v, $request) {
            /** @var Product $product */
            $product = Product::create([
                'name'        => $v['name'],
                'code'        => $v['code'] ?? null,
                'slug'        => $v['slug'],
                'type'        => $v['type'] ?? null,
                'description' => $v['description'] ?? null,
                'price'       => $v['price'],
                'category_id' => $v['category_id'] ?? null,
            ]);

            // Загрузка изображений (опционально)
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $i => $file) {
                    $stored = $file->store("products/{$product->id}", 'public');
                    $product->images()->create([
                        'path'       => $stored,
                        'alt'        => $product->name,
                        'sort_order' => $i + 1,
                        'is_primary' => false,
                    ]);
                }
            }

            // Проставим primary
            if (!empty($v['primary_image_id'])) {
                // если передали id существующего — выставим его
                $img = $product->images()->where('id', (int)$v['primary_image_id'])->first();
                if ($img) {
                    $product->images()->update(['is_primary' => false]);
                    $img->update(['is_primary' => true]);
                }
            }

            // Если primary нет — ставим первое по порядку
            if (!$product->images()->where('is_primary', true)->exists()) {
                $first = $product->images()->orderBy('sort_order')->first();
                if ($first) $first->update(['is_primary' => true]);
            }

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created.');
    }
}
