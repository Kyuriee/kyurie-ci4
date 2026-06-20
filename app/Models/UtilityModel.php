<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilityModel extends Model
{
    protected $table         = 'utilities';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'u_key',
        'u_value',
        'type',
        'description',
        'is_public',
    ];

    public function get_all_key_value(bool $public_only = false): array
    {
        $builder = $this;

        if ($public_only) {
            $builder = $builder->where('is_public', 'Y');
        }

        $rows = $builder->findAll();
        $data = [];

        foreach ($rows as $row) {
            $data[$row['u_key']] = $row['u_value'];
        }

        return $data;
    }

    public function get_value(string $key, $default = null)
    {
        $row = $this->where('u_key', $key)->first();

        return $row['u_value'] ?? $default;
    }

    public function set_value(string $key, $value): bool
    {
        $row = $this->where('u_key', $key)->first();

        if ($row) {
            return $this->update($row['id'], [
                'u_value' => $value,
            ]);
        }

        return $this->insert([
            'u_key'   => $key,
            'u_value' => $value,
        ]) ? true : false;
    }
}
