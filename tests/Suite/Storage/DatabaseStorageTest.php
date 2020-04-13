<?php

namespace Test\Suite\Storage;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Cases\Storage\MailStorageTestCase;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;
use VanEyk\MITM\Models\StoredMail;

/**
 * @covers \VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage
 */
class DatabaseStorageTest extends MailStorageTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function mitmConfig(): array
    {
        return ['storage_driver' => 'database'];
    }
}
