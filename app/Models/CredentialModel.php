<?php

namespace App\Models;

use CodeIgniter\Model;

class CredentialModel extends Model
{
    protected $table         = 'credentials';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'provider',
        'c_key',
        'c_value',
        'type',
        'mode',
        'status',
        'description',
    ];

    public function get_provider_credentials(string $provider, string $mode = 'production'): array
    {
        $rows = $this->where('provider', $provider)
            ->where('mode', $mode)
            ->where('status', 'On')
            ->findAll();

        $data = [];

        foreach ($rows as $row) {
            $data[$row['c_key']] = $row['c_value'];
        }

        return $data;
    }

    public function get_value(string $provider, string $key, string $mode = 'production', $default = null)
    {
        $row = $this->where('provider', $provider)
            ->where('c_key', $key)
            ->where('mode', $mode)
            ->where('status', 'On')
            ->first();

        return $row['c_value'] ?? $default;
    }

    public function set_value(string $provider, string $key, $value, string $mode = 'production'): bool
    {
        $row = $this->where('provider', $provider)
            ->where('c_key', $key)
            ->where('mode', $mode)
            ->first();

        if ($row) {
            return $this->update($row['id'], [
                'c_value' => $value,
            ]);
        }

        return $this->insert([
            'provider' => $provider,
            'c_key'    => $key,
            'c_value'  => $value,
            'mode'     => $mode,
        ]) ? true : false;
    }
}
