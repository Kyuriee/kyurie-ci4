<?php

namespace App\Services\Setting;

use App\Models\UtilityModel;
use App\Models\CredentialModel;
use App\Services\BaseService;

class SettingService extends BaseService
{
    protected $utilityModel;
    protected $credentialModel;

    public function __construct()
    {
        $this->utilityModel    = model(UtilityModel::class);
        $this->credentialModel = model(CredentialModel::class);
    }

    public function getPublicUtilities(): array
    {
        return $this->safeCall(
            fn() => $this->utilityModel->get_all_key_value(true),
            []
        );
    }

    public function getUtilities(): array
    {
        return $this->safeCall(
            fn() => $this->utilityModel->get_all_key_value(false),
            []
        );
    }

    public function getUtility(string $key, $default = null)
    {
        return $this->safeCall(
            fn() => $this->utilityModel->get_value($key, $default),
            $default
        );
    }

    public function setUtility(string $key, $value): bool
    {
        return $this->safeCall(
            fn() => $this->utilityModel->set_value($key, $value),
            false
        );
    }

    public function getCredentials(string $provider, string $mode = 'production'): array
    {
        return $this->safeCall(
            fn() => $this->credentialModel->get_provider_credentials($provider, $mode),
            []
        );
    }

    public function getCredential(string $provider, string $key, string $mode = 'production', $default = null)
    {
        return $this->safeCall(
            fn() => $this->credentialModel->get_value($provider, $key, $mode, $default),
            $default
        );
    }

    public function setCredential(string $provider, string $key, $value, string $mode = 'production'): bool
    {
        return $this->safeCall(
            fn() => $this->credentialModel->set_value($provider, $key, $value, $mode),
            false
        );
    }
}
