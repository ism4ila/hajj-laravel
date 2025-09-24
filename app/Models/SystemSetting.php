<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'category',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public static function get($key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->setting_value, $setting->setting_type);
    }

    public static function set($key, $value, $type = 'string', $category = 'general')
    {
        return static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_type' => $type,
                'category' => $category,
            ]
        );
    }

    private static function castValue($value, $type)
    {
        return match($type) {
            'boolean' => (bool) $value,
            'number' => is_numeric($value) ? (is_float($value) ? (float) $value : (int) $value) : $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
