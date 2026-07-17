<?php

namespace App\Services\Presentation;

use App\Services\Setting\SettingService;

class StorefrontContextBuilder
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function build(?array $currentUser = null, ?array $alert = null): array
    {
        $settings = $this->settingService->getPublicUtilities();

        return [
            'menus' => $this->buildMenus(),
            'meta'  => $this->buildMeta($settings),
            'seo'   => $this->buildSeo($settings),
            'user'  => $currentUser,
            'alert' => $alert,
            'page_assets' => [
                'css' => [],
                'js'  => [],
            ],
        ];
    }

    protected function buildMenus(): array
    {
        return [
            [
                'title' => 'Home',
                'url'   => base_url('/'),
                'icon'  => 'bi-house-door-fill',
                'match' => '',
            ],
            [
                'title' => 'Cek Pembayaran',
                'url'   => base_url('payment/check'),
                'icon'  => 'bi-receipt',
                'match' => 'payment',
            ],
            [
                'title' => 'Promo',
                'url'   => base_url('promo'),
                'icon'  => 'bi-ticket-perforated-fill',
                'match' => 'promo',
            ],
            [
                'title' => 'Bantuan',
                'url'   => base_url('help'),
                'icon'  => 'bi-question-circle-fill',
                'match' => 'help',
            ],
        ];
    }

    protected function buildMeta(array $settings): array
    {
        return [
            'title'       => $settings['web_title'] ?? 'RRQ & Evos Bersahabat',
            'subtitle'    => $settings['web_subtitle'] ?? '',
            'description' => $settings['web_description'] ?? '',
            'keywords'    => $settings['web_keywords'] ?? '',
            'author'      => $settings['web_author'] ?? '',
            'logo'        => $settings['web_logo'] ?? '',
            'favicon'     => $settings['web_favicon'] ?? '',
        ];
    }

    protected function buildSeo(array $settings): array
    {
        return [
            'og_title'       => $settings['web_title'] ?? '',
            'og_description' => $settings['web_description'] ?? '',
            'og_image'       => $settings['og_image'] ?? ($settings['web_logo'] ?? ''),
            'og_url'         => current_url(),
            'og_type'        => 'website',
            'twitter_card'   => 'summary_large_image',
        ];
    }
}
