<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecificationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'specification_id',
        'locale',
        'spec_key',
        'spec_value',
    ];

    public function specification()
    {
        return $this->belongsTo(ProductSpecification::class, 'specification_id');
    }
}
