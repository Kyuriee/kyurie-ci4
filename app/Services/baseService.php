<?php

namespace App\Services;

abstract class baseService
{
    /**
     * Jalanin callback dengan try-catch bawaan.
     * Kalau ada exception (misal query DB gagal), di-log dan return default
     * value biar gak nge-throw stack trace mentah ke response/user.
     *
     * @param callable $callback
     * @param mixed    $default
     * @return mixed
     */
    protected function safeCall(callable $callback, $default = [])
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            $this->logError($e->getMessage(), $e);

            return $default;
        }
    }

    /**
     * Log error dengan prefix nama class + lokasi exception, biar gampang
     * dilacak service mana yang gagal pas baca log.
     */
    protected function logError(string $message, ?\Throwable $exception = null): void
    {
        $context = '[' . static::class . '] ' . $message;

        if ($exception !== null) {
            $context .= ' at ' . $exception->getFile() . ':' . $exception->getLine();
        }

        log_message('error', $context);
    }

    /**
     * Log warning untuk kasus non-fatal tapi perlu diperhatikan, misalnya
     * silent fallback ke config default karena data sumbernya gak valid.
     */
    protected function logWarning(string $message): void
    {
        log_message('warning', '[' . static::class . '] ' . $message);
    }
}