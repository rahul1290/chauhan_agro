<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'page_id',
        'type',
        'order',
        'is_active',
        'settings_json',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'settings_json' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function translations()
    {
        return $this->hasMany(SectionTranslation::class, 'section_id');
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

    public function getButtonTextAttribute()
    {
        return $this->translation()?->button_text ?? '';
    }

    public function getButtonUrlAttribute()
    {
        return $this->translation()?->button_url ?? '';
    }

    public function getDataAttribute()
    {
        return $this->translation()?->data_json ?? [];
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->settings_json[$key] ?? $default;
    }
}
