<?php

namespace App\Services\Marketing;

use App\Services\BaseService;
use App\Models\BannerModel;

class BannerService extends BaseService
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = model(BannerModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn() => $this->bannerModel->getActive(),
            []
        );
    }
}
