<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'type', // custom, page, category
        'target_id',
        'target_url',
        'target_blank',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'target_blank' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order', 'asc');
    }

    public function allChildren()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function translations()
    {
        return $this->hasMany(MenuItemTranslation::class);
    }

    public function getTitleAttribute()
    {
        return $this->translation()?->title ?? '';
    }

    public function getUrlAttribute()
    {
        $locale = app()->getLocale();

        if ($this->type === 'page' && $this->target_id) {
            $page = Page::find($this->target_id);
            if ($page) {
                return url("/{$locale}/" . ($page->slug === 'home' ? '' : $page->slug));
            }
        } elseif ($this->type === 'category' && $this->target_id) {
            $cat = ProductCategory::find($this->target_id);
            if ($cat) {
                return url("/{$locale}/products?category={$cat->slug}");
            }
        }

        $transUrl = $this->translation()?->url;
        if (!empty($transUrl)) {
            return $transUrl;
        }

        if (!empty($this->target_url)) {
            if (str_starts_with($this->target_url, 'http://') || str_starts_with($this->target_url, 'https://') || str_starts_with($this->target_url, '#')) {
                return $this->target_url;
            }
            return url("/{$locale}/" . ltrim($this->target_url, '/'));
        }

        return '#';
    }
}
