<?php

namespace App\Services\Presentation;

class AdminContextBuilder
{
    public function build(?array $currentAdmin = null, ?array $alert = null): array
    {
        return [
            'meta'        => $this->buildMeta(),
            'admin'       => $currentAdmin,
            'alert'       => $alert,
            'page_assets' => [
                'css' => [],
                'js'  => [],
            ],
        ];
    }

    protected function buildMeta(): array
    {
        return [
            'title' => 'Admin — Kyurie',
        ];
    }
}
