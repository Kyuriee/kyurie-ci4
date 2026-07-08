<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table         = 'banner';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getActive(): array
    {
        return $this->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->findAll();
    }
}
