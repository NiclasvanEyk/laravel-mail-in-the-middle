<?php

namespace VanEyk\MITM\Support;

use VanEyk\MITM\Http\Controllers\AssetController;

class Route
{
    public static function resolve(string $name, array $params = []): string
    {
        return route(self::name($name), $params);
    }

    public static function name(string $name): string
    {
        return Config::KEY . '.' . $name;
    }

    public static function asset(string $path): string
    {
        return self::resolve(AssetController::ROUTE_NAME, [
            'path' => $path,
        ]);
    }
}
