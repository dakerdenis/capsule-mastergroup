<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← добавь
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory; // ← добавь

    protected $fillable = [
        'category_id', 'name', 'code', 'slug', 'type', 'description', 'price',
    ];

    protected $casts = ['price' => 'decimal:2'];

    public function category() { return $this->belongsTo(Category::class); }
    public function images()   { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function primaryImage() { return $this->hasOne(ProductImage::class)->where('is_primary', true); }

    protected static function booted()
    {
        static::saving(function (Product $model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->name);
                $slug = $base ?: Str::slug($model->code);
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $model->slug = $slug;
            }
        });
    }
}
