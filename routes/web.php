<?php

use Illuminate\Support\Facades\Route;
use VanEyk\MITM\Http\Controllers\AssetController;
use VanEyk\MITM\Http\Controllers\MailController;
use \VanEyk\MITM\Support\Config;
use VanEyk\MITM\Support\Route as PackageRoute;
use VanEyk\MITM\Support\View;

{
    $path = Config::get('path');

    Route::get("$path/assets", [AssetController::class, 'get'])
        ->name(PackageRoute::name(AssetController::ROUTE_NAME));

    Route::view($path, View::pageName('mails-overview'))
        ->name(PackageRoute::name('overview'));

    Route::view("$path/{id}", View::pageName('mail-detail'))
        ->name(PackageRoute::name('mail-detail'));

    Route::get("$path/{id}/content", [MailController::class, 'content'])
        ->name(PackageRoute::name('content'));
}

