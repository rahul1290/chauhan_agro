<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalPopup extends Model
{
    use HasFactory;

    protected $table = 'promotional_popups';

    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'target_blank',
        'border_color',
        'display_frequency',
        'display_delay',
        'is_active',
    ];

    protected $casts = [
        'target_blank' => 'boolean',
        'is_active' => 'boolean',
        'display_delay' => 'integer',
    ];

    /**
     * Get active popup instance for frontend display.
     */
    public static function getActivePopup(): ?self
    {
        return self::where('is_active', true)->latest('id')->first();
    }

    /**
     * Resolved image URL for frontend rendering.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'storage/') || str_starts_with($this->image_path, '/storage/')) {
            return asset(ltrim($this->image_path, '/'));
        }

        if (str_starts_with($this->image_path, 'assets/') || str_starts_with($this->image_path, '/assets/')) {
            return asset(ltrim($this->image_path, '/'));
        }

        return asset('storage/' . ltrim($this->image_path, '/'));
    }
}
