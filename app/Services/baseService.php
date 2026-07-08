<?php

namespace App\Services;

abstract class baseService
{
    protected function safeCall(callable $callback, $default = [])
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            $this->logError($e->getMessage(), $e);

            return $default;
        }
    }

    protected function logError(string $message, ?\Throwable $exception = null): void
    {
        $context = '[' . static::class . '] ' . $message;

        if ($exception !== null) {
            $context .= ' at ' . $exception->getFile() . ':' . $exception->getLine();
        }

        log_message('error', $context);
    }

    protected function logWarning(string $message): void
    {
        log_message('warning', '[' . static::class . '] ' . $message);
    }

    protected function success(string $message = 'Berhasil', array $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];
    }

    protected function fail(string $message, array $data = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ];
    }
}
