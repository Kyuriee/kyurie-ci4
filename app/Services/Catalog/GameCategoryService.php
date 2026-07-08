<?php

namespace App\Services\Catalog;

use App\Services\baseService;
use App\Models\gameCategoryModel;

class GameCategoryService extends baseService
{
    protected $gameCategoryModel;

    public function __construct()
    {
        $this->gameCategoryModel = model(GameCategoryModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn() => $this->gameCategoryModel->getActive(),
            []
        );
    }
}
