<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'category_id', 'brand_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->name);
        });
        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    private static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        $query = static::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = static::where('slug', $slug);
            if ($ignoreId) $query->where('id', '!=', $ignoreId);
        }
        return $slug;
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function primaryImage() { return $this->hasOne(ProductImage::class)->where('is_primary', true); }
    public function views() { return $this->hasMany(ProductView::class); }
}
