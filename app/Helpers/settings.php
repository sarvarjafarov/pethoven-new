<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by "group.key" notation.
     *
     * @param string $key e.g. "contact.phone1", "social.twitter"
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}
