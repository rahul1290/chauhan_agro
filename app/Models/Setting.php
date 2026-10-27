<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static array $cache = [];

    public static function get(string $key, $default = null)
    {
        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        $setting = static::where('key', $key)->first();
        $value = $setting ? $setting->value : $default;
        static::$cache[$key] = $value;

        return $value;
    }

    public static function set(string $key, $value, string $group = 'general')
    {
        static::$cache[$key] = $value;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
