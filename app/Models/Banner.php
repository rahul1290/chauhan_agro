<?php

namespace App\Models;

use App\Traits\HasTranslationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, HasTranslationsTrait;

    protected $fillable = [
        'page_slug',
        'image_path',
        'mobile_image_path',
        'button_url',
        'secondary_button_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function translations()
    {
        return $this->hasMany(BannerTranslation::class);
    }

    public function getTitleAttribute()
    {
        return $this->translation()?->title ?? '';
    }

    public function getSubtitleAttribute()
    {
        return $this->translation()?->subtitle ?? '';
    }

    public function getBadgeTextAttribute()
    {
        return $this->translation()?->badge_text ?? '';
    }

    public function getButtonTextAttribute()
    {
        return $this->translation()?->button_text ?? '';
    }

    public function getSecondaryButtonTextAttribute()
    {
        return $this->translation()?->secondary_button_text ?? '';
    }
}
