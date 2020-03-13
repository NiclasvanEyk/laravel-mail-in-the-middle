<?php

namespace VanEyk\MITM\Support;

class Path
{
    public const BASE = __DIR__ . '/../../';

    public static function path(string $path = ''): string
    {
        return self::BASE . $path;
    }

    public static function config(string $path = ''): string
    {
        return self::path("config/$path");
    }

    public static function routes(string $path = ''): string
    {
        return self::path("routes/$path");
    }

    public static function database(string $path = ''): string
    {
        return self::path("database/$path");
    }

    public static function migration(string $path = ''): string
    {
        return self::database("migrations/$path");
    }

    public static function resource(string $path = ''): string
    {
        return self::path("resources/$path");
    }

    public static function dist(string $path = ''): string
    {
        return self::path("dist/$path");
    }

    public static function view(string $path = ''): string
    {
        return self::resource("views/$path");
    }
}
