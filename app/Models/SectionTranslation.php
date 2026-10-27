<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'locale',
        'title',
        'subtitle',
        'content',
        'button_text',
        'button_url',
        'data_json',
    ];

    protected $casts = [
        'data_json' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(PageSection::class, 'section_id');
    }
}
