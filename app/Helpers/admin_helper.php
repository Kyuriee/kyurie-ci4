<?php

if (! function_exists('admin_url')) {
    /**
     * Builds a URL under the admin panel's route prefix.
     *
     * The prefix is intentionally NOT a predictable path like "admin" —
     * it's a hard-to-guess slug pulled from ADMIN_PATH in .env, so it
     * never gets hardcoded in multiple places. Change it in .env only;
     * never add this path to robots.txt (that file is public and would
     * broadcast the exact path to anyone scraping it — use the
     * X-Robots-Tag response header instead, see AdminNoIndexFilter).
     */
    function admin_url(string $path = ''): string
    {
        $prefix = trim(env('ADMIN_PATH', 'kyurie-backoffice-2026'), '/');
        $path   = trim($path, '/');

        return base_url($prefix . ($path !== '' ? '/' . $path : ''));
    }

    function admin_path(string $path = ''): string
    {
        $prefix = trim(env('ADMIN_PATH', 'kyurie-backoffice-2026'), '/');
        $path   = trim($path, '/');

        return $prefix . ($path !== '' ? '/' . $path : '');
    }
}
