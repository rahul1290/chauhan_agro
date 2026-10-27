<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'product_id',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductSpecificationTranslation::class, 'specification_id');
    }

    public function getKeyAttribute()
    {
        return $this->translation()?->spec_key ?? '';
    }

    public function getValueAttribute()
    {
        return $this->translation()?->spec_value ?? '';
    }
}
