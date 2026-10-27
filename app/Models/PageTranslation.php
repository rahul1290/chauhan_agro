<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'locale',
        'title',
        'slug',
        'subtitle',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
