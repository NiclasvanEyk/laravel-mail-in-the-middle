<?php

use \VanEyk\MITM\Support\Config;
use VanEyk\MITM\Http\Controllers\MailController;

{
    $path = Config::get('path');

    Route::prefix("$path/api")->group(function () {
        Route::get('/mails', [MailController::class, 'index']);
        Route::get('/mails/{id}', [MailController::class, 'show']);
        Route::get(
            '/mails/{mailId}/attachments/{attachmentId}',
            [MailController::class, 'downloadAttachment']
        );
        Route::delete('/mails/{id}', [MailController::class, 'destroy']);
    });
}
