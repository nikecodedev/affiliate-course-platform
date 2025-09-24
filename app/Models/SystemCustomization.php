<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemCustomization extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get a customization value by key
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("customization.{$key}", 3600, function () use ($key, $default) {
            $customization = static::where('key', $key)
                ->where('is_active', true)
                ->first();
            
            return $customization ? $customization->value : $default;
        });
    }

    /**
     * Set a customization value by key
     */
    public static function setValue(string $key, $value, string $type = 'text', string $description = null)
    {
        $customization = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
                'is_active' => true
            ]
        );

        // Clear cache
        Cache::forget("customization.{$key}");

        return $customization;
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $logoPath = static::getValue('logo_path');
        return $logoPath ? asset('storage/' . $logoPath) : asset('images/default-logo.svg');
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl()
    {
        $faviconPath = static::getValue('favicon_path');
        return $faviconPath ? asset('storage/' . $faviconPath) : asset('images/default-favicon.svg');
    }

    /**
     * Get background image URL
     */
    public static function getBackgroundUrl()
    {
        $bgPath = static::getValue('background_path');
        return $bgPath ? asset('storage/' . $bgPath) : null;
    }

    /**
     * Get primary color
     */
    public static function getPrimaryColor()
    {
        return static::getValue('primary_color', '#007bff');
    }

    /**
     * Get secondary color
     */
    public static function getSecondaryColor()
    {
        return static::getValue('secondary_color', '#6c757d');
    }

    /**
     * Get company name
     */
    public static function getCompanyName()
    {
        return static::getValue('company_name', 'Your Platform');
    }

    /**
     * Get company email
     */
    public static function getCompanyEmail()
    {
        return static::getValue('company_email', 'contact@yourplatform.com');
    }

    /**
     * Get company phone
     */
    public static function getCompanyPhone()
    {
        return static::getValue('company_phone', '+1 (555) 123-4567');
    }

    /**
     * Get company address
     */
    public static function getCompanyAddress()
    {
        return static::getValue('company_address', '123 Business St, City, State 12345');
    }
}
