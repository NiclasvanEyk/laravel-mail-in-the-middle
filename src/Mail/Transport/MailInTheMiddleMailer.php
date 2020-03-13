<?php

namespace VanEyk\MITM\Mail\Transport;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;
use VanEyk\MITM\Mail\Meta\MailableAnalyzer;
use VanEyk\MITM\Storage\MailStorage;
use VanEyk\MITM\Models\StoredMail;

class MailInTheMiddleMailer extends Transport
{
    /** @var MailStorage */
    private $storage;

    public function __construct(MailStorage $storage)
    {
        $this->storage = $storage;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null): int
    {
        $mailable = $this->findMailableViaBacktrace();
        $analyzer = new MailableAnalyzer($mailable);
        $source = $analyzer->source();
        $viewData = $analyzer->viewDataDump();

        $from = [];

        foreach ($message->getFrom() as $email => $alias) {
            $from[] = "$alias <$email>";
        }

        $to = [];

        foreach ($message->getTo() as $email => $alias) {
            $to[] = "$alias <$email>";
        }

        $attributes = [
            'subject' => $message->getSubject(),
            'from' => implode(', ', $from),
            'to' => implode(', ', $to),
            'cc' => $message->getCc(),
            'bcc' => $message->getBcc(),
            'src' => $source,
            'rendered' => $message->getBody(),
            'viewData' => $viewData,
            'priority' => $message->getPriority(),
        ];
        $mail = new StoredMail($attributes);

        $this->storage->save($message, $mail);

        return 1;
    }

    private function findMailableViaBacktrace(): ?Mailable
    {
        $backtrace = debug_backtrace();

        foreach ($backtrace as $trace) {
            if ($trace['class'] === Mailable::class) {
                return $trace['object'];
            }
        }

        return null;
    }
}
