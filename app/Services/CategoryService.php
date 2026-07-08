<?php

namespace App\Services;

use App\Models\CategoryModel;

class CategoryService extends baseService
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn () => $this->categoryModel->getActive(),
            []
        );
    }
}
