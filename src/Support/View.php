<?php

namespace VanEyk\MITM\Support;

class View
{
    public static function page(
        string $name,
        array $data = [],
        array $mergeData = []
    ): \Illuminate\View\View {
        return view(static::pageName($name), $data, $mergeData);
    }

    public static function pageName(string $name): string
    {
        return Config::KEY . '::pages.' . $name;
    }

    public static function component(string $name): string
    {
        return Config::KEY . "::components.$name";
    }
}
