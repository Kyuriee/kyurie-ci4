<?php

namespace App\Services;

class TargetService extends baseService
{
    protected const MAX_ZONE_INPUTS = 6;

    public function getFormConfig(?string $target, $inputCustom = null): array
    {
        $target = strtolower(trim((string) $target));

        if ($target !== 'custom') {
            $target = 'default';
        }

        $customInputs = $target === 'custom'
            ? $this->decodeCustomInputs($inputCustom)
            : [];

        if ($target === 'custom' && ! empty($customInputs)) {
            return $this->buildConfig('custom', $customInputs);
        }

        return $this->buildConfig('default', [
            $this->accountInput('customer_id', 'User ID / Player ID', 'Masukkan ID akunmu'),
        ]);
    }

    public function validatePayload(?string $target, $inputCustom, array $payload): array
    {
        $config = $this->getFormConfig($target, $inputCustom);
        $values = $this->normalizePayloadValues($config, $payload);

        foreach ($config['inputs'] as $input) {
            $key = $input['key'];
            $value = $values[$key] ?? '';

            if (! empty($input['required']) && $value === '') {
                return [
                    'success' => false,
                    'message' => $input['label'] . ' wajib diisi',
                    'data'    => $this->buildTargetData($config, $values),
                ];
            }

            if (($input['type'] ?? 'text') === 'select' && $value !== '' && ! $this->optionValueExists($input, $value)) {
                return [
                    'success' => false,
                    'message' => $input['label'] . ' tidak valid',
                    'data'    => $this->buildTargetData($config, $values),
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Target valid',
            'data'    => $this->buildTargetData($config, $values),
        ];
    }

    protected function decodeCustomInputs($inputCustom): array
    {
        if (empty($inputCustom)) {
            return [];
        }

        if (is_string($inputCustom)) {
            $decoded = json_decode($inputCustom, true);

            if (! is_array($decoded)) {
                return [];
            }
        } elseif (is_array($inputCustom)) {
            $decoded = $inputCustom;
        } else {
            return [];
        }

        $rawInputs = $decoded['inputs'] ?? $decoded;

        if (! is_array($rawInputs)) {
            return [];
        }

        $inputs = [];
        $index = 0;

        foreach ($rawInputs as $rawInput) {
            if (! is_array($rawInput)) {
                continue;
            }

            $input = $this->inputFromArray($rawInput, $index);

            if (! empty($input)) {
                $inputs[$input['key']] = $input;
                $index++;
            }

            if ($index > self::MAX_ZONE_INPUTS) {
                break;
            }
        }

        return array_values($inputs);
    }

    protected function inputFromArray(array $rawInput, int $index): array
    {
        if ($index === 0) {
            $key = 'customer_id';
            $role = 'account';
        } else {
            $zoneIndex = $index - 1;
            $key = $zoneIndex === 0 ? 'zone_id' : 'zone_id_' . $zoneIndex;
            $role = 'zone';
        }

        if ($index > self::MAX_ZONE_INPUTS) {
            return [];
        }

        $type = strtolower(trim((string) ($rawInput['type'] ?? 'text')));
        $required = array_key_exists('required', $rawInput) ? (bool) $rawInput['required'] : true;
        $label = trim((string) ($rawInput['label'] ?? $this->labelFromKey($key)));
        $placeholder = trim((string) ($rawInput['placeholder'] ?? ''));
        $options = $this->normalizeOptions($rawInput['options'] ?? []);

        if (! in_array($type, ['text', 'select'], true)) {
            $type = 'text';
        }

        if ($type === 'select' && empty($options)) {
            $type = 'text';
        }

        if ($placeholder === '') {
            $placeholder = $type === 'select' ? 'Pilih salah satu' : 'Masukkan ' . strtolower($label);
        }

        return $this->input($key, $role, $type, $label, $placeholder, $required, $options);
    }

    protected function buildConfig(string $type, array $inputs): array
    {
        return [
            'type'   => $type,
            'inputs' => $inputs,
        ];
    }

    protected function accountInput(string $key, string $label, string $placeholder, bool $required = true): array
    {
        return $this->input($key, 'account', 'text', $label, $placeholder, $required);
    }

    protected function input(string $key, string $role, string $type, string $label, string $placeholder, bool $required, array $options = []): array
    {
        return [
            'key'         => $key,
            'name'        => $key,
            'role'        => $role,
            'type'        => $type,
            'label'       => $label,
            'placeholder' => $placeholder,
            'required'    => $required,
            'options'     => $options,
        ];
    }

    protected function normalizePayloadValues(array $config, array $payload): array
    {
        $targetValues = $payload['target_values'] ?? [];

        if (is_string($targetValues)) {
            $decoded = json_decode($targetValues, true);
            $targetValues = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($targetValues)) {
            $targetValues = [];
        }

        $values = [];

        foreach ($config['inputs'] as $input) {
            $key = $input['key'];
            $value = $payload[$key] ?? $targetValues[$key] ?? null;

            if ($value === null && $input['role'] === 'account') {
                $value = $payload['user_id_game'] ?? '';
            }

            if ($value === null && $input['role'] === 'zone') {
                $value = $key === 'zone_id' ? ($payload['zone_id'] ?? '') : '';
            }

            $values[$key] = trim((string) ($value ?? ''));
        }

        return $values;
    }

    protected function buildTargetData(array $config, array $values): array
    {
        $customerId = '';
        $zoneValues = [];

        foreach ($config['inputs'] as $input) {
            $key = $input['key'];
            $value = $values[$key] ?? '';

            if ($value === '') {
                continue;
            }

            if (($input['role'] ?? '') === 'account' && $customerId === '') {
                $customerId = $value;
                continue;
            }

            if (($input['role'] ?? '') === 'zone') {
                $zoneValues[] = $value;
            }
        }

        return [
            'target_values' => $values,
            'customer_id'   => $customerId,
            'zone_id'       => implode(',', $zoneValues),
        ];
    }

    protected function labelFromKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }

    protected function normalizeOptions($rawOptions): array
    {
        if (! is_array($rawOptions)) {
            return [];
        }

        $options = [];

        foreach ($rawOptions as $key => $rawOption) {
            if (is_array($rawOption)) {
                $label = trim((string) ($rawOption['label'] ?? $rawOption['name'] ?? ''));
                $value = trim((string) ($rawOption['value'] ?? $rawOption['id'] ?? ''));
            } else {
                $label = is_string($key) ? trim((string) $rawOption) : trim((string) $rawOption);
                $value = is_string($key) ? trim((string) $key) : trim((string) $rawOption);
            }

            if ($label === '' || $value === '') {
                continue;
            }

            $options[] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        return $options;
    }

    protected function optionValueExists(array $input, string $value): bool
    {
        foreach ($input['options'] ?? [] as $option) {
            if ((string) ($option['value'] ?? '') === $value) {
                return true;
            }
        }

        return false;
    }
}
