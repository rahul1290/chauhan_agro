<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'parent_id',
        'slug',
        'image',
        'icon',
        'order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->orderBy('order', 'asc');
    }

    public function translations()
    {
        return $this->hasMany(ProductCategoryTranslation::class, 'category_id');
    }

    public function getNameAttribute()
    {
        return $this->translation()?->name ?? '';
    }

    public function getDescriptionAttribute()
    {
        return $this->translation()?->description ?? '';
    }

    public function getMetaTitleAttribute()
    {
        return $this->translation()?->meta_title ?? $this->name;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->translation()?->meta_description ?? $this->description;
    }
}
