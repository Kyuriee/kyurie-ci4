<?php

namespace App\Services;

use App\Models\BannerModel;

class BannerService extends baseService
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = model(BannerModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn () => $this->bannerModel->getActive(),
            []
        );
    }
}
