<?php

namespace VanEyk\MITM\Support;

class Config
{
    public const KEY = 'mail-in-the-middle';
    public const COMMAND_PREFIX = 'mitm';

    public const FILE_NAME = self::KEY . '.php';

    public static function get(string $path, $default = null)
    {
        return config(static::KEY . '.' . $path, $default);
    }
}
