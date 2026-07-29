<?php

namespace App\Services\Presentation;

use App\Services\Setting\SettingService;

class BackofficeContextBuilder
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function build(?array $currentAdmin = null, ?array $alert = null): array
    {
        $settings = $this->settingService->getPublicUtilities();

        return [
            'meta'  => $this->buildMeta($settings),
            'admin'  => $currentAdmin,
            'alert' => $alert,
            'page_assets' => [
                'css' => [],
                'js'  => [],
            ],
        ];
    }

    protected function buildMeta(array $settings): array
    {
        return [
            'title'       => $settings['web_title'] ?? 'RRQ & Evos Bersahabat Admin',
            'subtitle'    => $settings['web_subtitle'] ?? '',
            'description' => $settings['web_description'] ?? '',
            'keywords'    => $settings['web_keywords'] ?? '',
            'author'      => $settings['web_author'] ?? '',
            'logo'        => $settings['web_logo'] ?? '',
            'favicon'     => $settings['web_favicon'] ?? '',
        ];
    }
}
