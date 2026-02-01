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

if (!function_exists('media_disk')) {
    /**
     * Get the configured media disk name.
     */
    function media_disk(): string
    {
        return config('filesystems.media', 'public');
    }
}

if (!function_exists('media_url')) {
    /**
     * Get the public URL for a media file path.
     * Returns null if path is empty.
     */
    function media_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk(media_disk())->url($path);
    }
}
