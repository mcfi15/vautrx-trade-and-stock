<?php

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
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings by group
     *
     * @param string $group
     * @return array
     */
    function settings($group = 'general')
    {
        return \App\Models\Setting::getByGroup($group);
    }
}
