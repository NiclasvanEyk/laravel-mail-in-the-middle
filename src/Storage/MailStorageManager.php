<?php

namespace VanEyk\MITM\Storage;

use Illuminate\Support\Manager;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;
use VanEyk\MITM\Storage\Implementations\Filesystem\FilesystemStorage;
use VanEyk\MITM\Support\Config;

class MailStorageManager extends Manager
{
    public function getDefaultDriver()
    {
        return Config::get('storage_driver') ?? 'filesystem';
    }

    public function createFilesystemDriver()
    {
        return app(FilesystemStorage::class);
    }

    public function createDatabaseDriver()
    {
        return app(DatabaseStorage::class);
    }
}
