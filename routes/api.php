<?php

use \VanEyk\MITM\Support\Config;
use VanEyk\MITM\Http\Controllers\MailController;

{
    $path = Config::get('path');

    Route::prefix("$path/api")->group(function () {
        Route::get('/mails', [MailController::class, 'index']);
        
        Route::get(
            '/mails/{mailId}/attachments/{attachmentId}',
            [MailController::class, 'downloadAttachment']
        );
    });
}
