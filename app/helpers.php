<?php

use App\Models\SystemSetting;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return SystemSetting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @param string|null $description
     * @return \App\Models\SystemSetting
     */
    function set_setting($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        return SystemSetting::set($key, $value, $type, $group, $description);
    }
}
