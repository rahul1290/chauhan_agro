<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'slug',
        'template',
        'featured_image',
        'is_published',
        'order',
        'show_in_nav',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_nav' => 'boolean',
        'order' => 'integer',
    ];

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order', 'asc');
    }

    public function activeSections()
    {
        return $this->hasMany(PageSection::class)->where('is_active', true)->orderBy('order', 'asc');
    }

    public function getTitleAttribute()
    {
        return $this->translation()?->title ?? '';
    }

    public function getSubtitleAttribute()
    {
        return $this->translation()?->subtitle ?? '';
    }

    public function getContentAttribute()
    {
        return $this->translation()?->content ?? '';
    }

    public function getExcerptAttribute()
    {
        return $this->translation()?->excerpt ?? '';
    }

    public function getMetaTitleAttribute()
    {
        return $this->translation()?->meta_title ?? $this->title;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->translation()?->meta_description ?? $this->excerpt;
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->translation()?->meta_keywords ?? '';
    }
}
