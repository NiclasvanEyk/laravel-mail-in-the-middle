<?php

namespace Test\Suite\Storage;

use Illuminate\Support\Facades\Storage;
use Test\Cases\Storage\MailStorageTestCase;
use VanEyk\MITM\Storage\Implementations\Filesystem\FilesystemStorage;

class FilesystemStorageTest extends MailStorageTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('filesystems.disks', [
            'mitm-testing' => [
                'driver' => 'local',
                'root' => storage_path('app/mitm-testing'),
            ],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('mitm-testing');
    }

    public function mitmConfig(): array
    {
        return [
            'disk' => 'mitm-testing',
            // This storage_driver should be the default
        ];
    }
}
