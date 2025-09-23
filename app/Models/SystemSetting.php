<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->get()->keyBy('key');
    }

    /**
     * Get SEO settings
     */
    public static function getSEOSettings()
    {
        return static::getByGroup('seo');
    }

    /**
     * Get tracking settings
     */
    public static function getTrackingSettings()
    {
        return static::getByGroup('tracking');
    }

    /**
     * Get appearance settings
     */
    public static function getAppearanceSettings()
    {
        return static::getByGroup('appearance');
    }

    /**
     * Get financial settings
     */
    public static function getFinancialSettings()
    {
        return static::getByGroup('financial');
    }
}

