<?php

namespace VanEyk\MITM\Support;

class Config
{
    public const KEY = 'mail-in-the-middle';
    public const COMMAND_PREFIX = 'mitm';

    public const FILE_NAME = self::KEY . '.php';

    public static function isBeforeLaravel7(): bool
    {
        return array_key_exists('driver', config('mail'));
    }

    public static function mailDriverInUse(): string
    {
        // In Laravel 7 the configuration of the Mailers changed a bit, which is
        // why we have to differentiate between the two formats
        $transport = self::isBeforeLaravel7()
            ? config('mail.driver')
            : config('mail.mailers')[config('mail.default')]['transport'];

        return $transport;
    }

    public static function usesMailInTheMiddle(): bool
    {
        return self::mailDriverInUse() === Config::KEY;
    }

    public static function get(string $path, $default = null)
    {
        return config(static::KEY . '.' . $path, $default);
    }
}
