<?php

namespace VanEyk\MITM\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use VanEyk\MITM\Support\Config;

class TestMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this
            ->from('test@mail-in-the-middle.com')
            ->markdown(Config::KEY . "::mail.test");
    }
}
