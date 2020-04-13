<?php

namespace VanEyk\MITM\Exceptions;

use Throwable;
use VanEyk\MITM\Models\StoredMail;

class CouldNotStoreMailException extends MailInTheMiddleException
{
    /** @var StoredMail */
    private $mail;

    public function __construct(StoredMail $mail, $message = null, $code = 0, Throwable $previous = null)
    {
        $message = $message ?? "Could not store mail '$mail->subject' ($mail->id)!";
        parent::__construct($message, $code, $previous);
        $this->mail = $mail;
    }
}
