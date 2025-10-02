<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'is_active', 'sort_order', 'description'
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('sort_order')->orderBy('name');
    }

    public function scopeRoots($q)
    {
        return $q->whereNull('parent_id')->orderBy('sort_order')->orderBy('name');
    }

    protected static function booted()
    {
        static::saving(function (Category $model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->name);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $model->slug = $slug;
            }
        });
    }
}
