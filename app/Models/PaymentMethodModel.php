<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table         = 'payment_methods';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = false;

    public function getActive(): array
    {
        return $this->where('status', 'On')
            ->orderBy('sort', 'ASC')
            ->findAll();
    }

    public function find($id = null): array
    {
        $data = parent::find($id);

        return $data ?? [];
    }
}
