<?php

namespace App\Services\Setting;

use App\Models\UtilityModel;
use App\Models\CredentialModel;
use App\Services\baseService;

class SettingService extends baseService
{
    protected $utility_model;
    protected $credential_model;

    public function __construct()
    {
        $this->utility_model    = model(UtilityModel::class);
        $this->credential_model = model(CredentialModel::class);
    }

    public function get_public_utilities(): array
    {
        return $this->utility_model->get_all_key_value(true);
    }

    public function get_utilities(): array
    {
        return $this->utility_model->get_all_key_value(false);
    }

    public function get_utility(string $key, $default = null)
    {
        return $this->utility_model->get_value($key, $default);
    }

    public function set_utility(string $key, $value): bool
    {
        return $this->utility_model->set_value($key, $value);
    }

    public function get_credentials(string $provider, string $mode = 'production'): array
    {
        return $this->credential_model->get_provider_credentials($provider, $mode);
    }

    public function get_credential(string $provider, string $key, string $mode = 'production', $default = null)
    {
        return $this->credential_model->get_value($provider, $key, $mode, $default);
    }

    public function set_credential(string $provider, string $key, $value, string $mode = 'production'): bool
    {
        return $this->credential_model->set_value($provider, $key, $value, $mode);
    }
}
