<?php

namespace Test\Factories\Mail;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Swift_Attachment;
use VanEyk\MITM\Models\StoredMail;

/**
 * @mixin Mailable
 */
class TestMails
{
    public static function emptySwift()
    {
        return new \Swift_Message();
    }

    public static function emptyStoredMail(array $attributes = []): StoredMail
    {
        return new StoredMail(array_merge([
            'subject' => '',
            'from' => '',
            'to' => '',
            'cc' => '',
            'bcc' => '',
            'priority' => '',
            'src' => '',
            'rendered' => '',
            'viewData' => '',
        ], $attributes));
    }

    public static function swiftWithAttachment(string $filename)
    {
        $base = static::emptySwift();

        $base->attach(Swift_Attachment::fromPath(
            __DIR__ . '/../../resources/images/test.png'
        )->setFilename($filename));

        return $base;
    }
}
