<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $tree = Category::with('children.children') // 2 уровня сразу; фронт добросит остальное через рекурсию
            ->roots()
            ->get();

        $all = Category::orderBy('name')->get(['id','name']); // для селекта родителя

        return view('admin.categories.index', [
            'title' => 'Categories',
            'tree'  => $tree,
            'all'   => $all,
        ]);
    }

    public function create()
    {
        return view('admin.categories.create', [
            'title' => 'Create category',
            'all'   => Category::orderBy('name')->get(['id','name']),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $max = Category::where('parent_id', $request->parent_id)->max('sort_order') ?? 0;

        Category::create([
            ...$request->validated(),
            'sort_order' => $max + 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'title'    => 'Edit category',
            'category' => $category,
            'all'      => Category::where('id','!=',$category->id)->orderBy('name')->get(['id','name']),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category updated');
    }

    public function destroy(Category $category)
    {
        // мягкая защита: не удаляем, если есть дети
        if ($category->children()->exists()) {
            return back()->with('error', 'Remove or move subcategories first.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted');
    }

    /**
     * Принять JSON-дерево и обновить parent_id + sort_order
     * Формат items: [ {id, children:[...]}, ... ]
     */
    public function reorder(Request $request)
    {
        $items = $request->input('items', []);
        DB::transaction(function () use ($items) {
            $this->applyOrder($items, null);
        });

        return response()->json(['ok' => true]);
    }

    private function applyOrder(array $items, $parentId): void
    {
        foreach ($items as $index => $node) {
            /** @var Category $cat */
            $cat = Category::findOrFail($node['id']);
            $cat->update([
                'parent_id'  => $parentId,
                'sort_order' => $index,
            ]);

            if (!empty($node['children'])) {
                $this->applyOrder($node['children'], $cat->id);
            }
        }
    }
}
