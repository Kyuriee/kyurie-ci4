<?php

if (! function_exists('vite')) {
    function vite(string $entry = 'resources/js/app.js'): string
    {
        $manifest_path = FCPATH . 'build/.vite/manifest.json';

        if (! file_exists($manifest_path)) {
            return '<!-- Vite manifest not found. Run npm run build. -->';
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (! isset($manifest[$entry])) {
            return '<!-- Vite entry not found: ' . esc($entry) . ' -->';
        }

        $tags = '';

        if (! empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $css) {
                $tags .= '<link rel="stylesheet" href="' . base_url('build/' . $css) . '">' . PHP_EOL;
            }
        }

        $file = $manifest[$entry]['file'];
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
            $tags .= '<link rel="stylesheet" href="' . base_url('build/' . $file) . '">' . PHP_EOL;
        } else {
            $tags .= '<script type="module" src="' . base_url('build/' . $file) . '"></script>' . PHP_EOL;
        }

        return $tags;
    }
}

if (! function_exists('vite_css')) {
    function vite_css(string $entry): string
    {
        $manifest_path = FCPATH . 'build/.vite/manifest.json';

        if (! file_exists($manifest_path)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (! isset($manifest[$entry])) {
            return '<!-- Vite CSS entry not found: ' . esc($entry) . ' -->';
        }

        $tags = '';

        if (! empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $css) {
                $tags .= '<link rel="stylesheet" href="' . base_url('build/' . $css) . '">' . PHP_EOL;
            }
        }

        $file = $manifest[$entry]['file'];
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
            $tags .= '<link rel="stylesheet" href="' . base_url('build/' . $file) . '">' . PHP_EOL;
        }

        return $tags;
    }
}

if (! function_exists('vite_js')) {
    function vite_js(string $entry): string
    {
        $manifest_path = FCPATH . 'build/.vite/manifest.json';

        if (! file_exists($manifest_path)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (! isset($manifest[$entry])) {
            return '<!-- Vite JS entry not found: ' . esc($entry) . ' -->';
        }

        $file = $manifest[$entry]['file'];
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'css') {
            return '<script type="module" src="' . base_url('build/' . $file) . '"></script>' . PHP_EOL;
        }

        return '';
    }
}