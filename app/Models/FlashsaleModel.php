<?php

namespace App\Models;

use CodeIgniter\Model;

class FlashsaleModel extends Model
{
    protected $table         = 'flashsale';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getActive(): array
    {
        return $this->where('status', 'On')
            ->orderBy('id', 'DESC')
            ->first() ?? [];
    }
}
