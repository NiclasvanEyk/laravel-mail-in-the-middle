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

    // In Laravel 7 the configuration of the Mailers changed a bit, which is
    // why we have to differentiate between the two formats
    $isBeforeLaravel7 = array_key_exists('driver', config('mail'));
    $transport = $isBeforeLaravel7
        ? config('mail.driver')
        : config('mail.mailers')[config('mail.default')]['transport'];

    Route::view($path, View::pageName('mails-overview'), [
        'mailerUnused' => $transport !== Config::KEY,
        'transport' => $transport,
        'envKey' => $isBeforeLaravel7 ? 'MAIL_DRIVER' : 'MAIL_MAILER',
    ])->name(PackageRoute::name('overview'));

    Route::view("$path/{id}", View::pageName('mail-detail'))
        ->name(PackageRoute::name('mail-detail'));

    Route::get("$path/{id}/content", [MailController::class, 'content'])
        ->name(PackageRoute::name('content'));
}

