<?php

namespace VanEyk\MITM\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use VanEyk\MITM\Storage\MailStorage;
use VanEyk\MITM\Support\Config;

class HouseKeepingCommand extends Command
{
    public const COMMAND = Config::COMMAND_PREFIX
        . ':'
        . 'clear-mails-older-than';

    protected $signature = self::COMMAND . "
        {relativeDateInPast='last week' : A description of a date that php can parse}
    ";
    protected $description = 'Deletes all stored mails older than the specified date';

    public function handle(): int
    {
        $olderThanInput = $this->argument('relativeDateInPast');
        $olderThan = new Carbon($olderThanInput);

        if (! $olderThan->isValid()) {
            $this->error(
                "'$olderThanInput' could not be parsed to a valid date!"
                    . ' No mails were deleted.'
            );
            return -1;
        }

        /** @var MailStorage $storage */
        $storage = resolve(MailStorage::class);

        $numDeleted = $storage->clearOlderThan($olderThan);

        $this->info("$numDeleted old mails were deleted!");
        $numStored = $storage->count();
        $this->info("There are still $numStored mails stored.");

        return 0;
    }
}
