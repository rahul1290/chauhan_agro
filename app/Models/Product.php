<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'category_id',
        'sku',
        'slug',
        'featured_image',
        'brochure_pdf',
        'tractor_hp_min',
        'tractor_hp_max',
        'working_width',
        'weight_approx',
        'blades_count',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'tractor_hp_min' => 'integer',
        'tractor_hp_max' => 'integer',
        'blades_count' => 'integer',
        'order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('order', 'asc');
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_id');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function getNameAttribute()
    {
        return $this->translation()?->name ?? '';
    }

    public function getShortDescriptionAttribute()
    {
        return $this->translation()?->short_description ?? '';
    }

    public function getDescriptionAttribute()
    {
        return $this->translation()?->description ?? '';
    }

    public function getFeaturesAttribute()
    {
        return $this->translation()?->features_json ?? [];
    }

    public function getMetaTitleAttribute()
    {
        return $this->translation()?->meta_title ?? $this->name;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->translation()?->meta_description ?? $this->short_description;
    }

    public function getHpRangeAttribute()
    {
        if ($this->tractor_hp_min && $this->tractor_hp_max) {
            return "{$this->tractor_hp_min} - {$this->tractor_hp_max} HP";
        } elseif ($this->tractor_hp_min) {
            return "{$this->tractor_hp_min}+ HP";
        }
        return null;
    }
}
