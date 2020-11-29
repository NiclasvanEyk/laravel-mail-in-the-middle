<?php

namespace VanEyk\MITM\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use VanEyk\MITM\Mail\TestMailable;
use VanEyk\MITM\Support\Config;

class SendTestMailCommand extends Command 
{
    protected $signature = Config::COMMAND_PREFIX . ':send-test-mail';
    protected $description = 'Sends a test mail for verification purposes';

    public function handle(): int
    {
        if (!Config::usesMailInTheMiddle()) {
            $driver = Config::mailDriverInUse();
            $this->error("Your mail driver seems to be '$driver', so mail will be sent!");
        }

        $this->info('Sending out a test mail...');

        Mail::to('recipient@your-application.com')->send(new TestMailable());

        $this->info('Done!');

        return 0;
    }
}