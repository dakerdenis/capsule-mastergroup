<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← добавь

class ProductImage extends Model
{
    use HasFactory; // ← добавь

    protected $fillable = ['product_id','path','alt','sort_order','is_primary'];
    protected $casts    = ['is_primary' => 'bool'];

    public function product() { return $this->belongsTo(Product::class); }
}
