<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_id',
        'locale',
        'title',
        'subtitle',
        'badge_text',
        'button_text',
        'secondary_button_text',
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
