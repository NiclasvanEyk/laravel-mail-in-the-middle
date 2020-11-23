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

    Route::get("$path", [MailController::class, 'index'])
        ->name(PackageRoute::name('mail-overview'));

    Route::post("$path/send-test-mail", [MailController::class, 'sendTestMail'])
        ->name(PackageRoute::name('send-test-mail'));

    Route::delete("$path", [MailController::class, 'clearAll'])
        ->name(PackageRoute::name('clear-all'));

    Route::delete('/mails/{id}', [MailController::class, 'destroy'])
        ->name(PackageRoute::name('mail-destroy'));

    Route::get("$path/mails/{mailId}/attachments/{attachmentId}", [MailController::class, 'downloadAttachment'])
        ->name(PackageRoute::name('attachment.download'));

    Route::get("$path/{id}", [MailController::class, 'show'])
        ->name(PackageRoute::name('mail-detail'));

    Route::get("$path/{id}/content", [MailController::class, 'content'])
        ->name(PackageRoute::name('content'));
}

