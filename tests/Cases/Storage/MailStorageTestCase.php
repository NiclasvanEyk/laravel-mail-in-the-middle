<?php

namespace Test\Cases\Storage;

use Carbon\Carbon;
use Test\Factories\Mail\TestMails;
use Test\MITMTestCase;
use VanEyk\MITM\Console\Commands\HouseKeepingCommand;
use VanEyk\MITM\Storage\MailStorage;

/**
 * This test case describes tests all the capabilities of a
 * {@link MailStorage}.
 */
abstract class MailStorageTestCase extends MITMTestCase
{
    abstract function storageConfig(): array;

    /** @var MailStorage */
    private $storage;

    protected final function mitmConfig(): array
    {
        $config = $this->storageConfig();
        $driverName = array_keys($config)[0];
        $driverConfig = $config[$driverName];

        return [
            'storage_driver' => $driverName,
            'storage_drivers' => [
                $driverName => $driverConfig,
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = resolve(MailStorage::class);
    }

    /**
     * This is the most important test! If mails cannot be stored, we skip
     * all further tests, because otherwise they would not make any sense.
     *
     * @test
     */
    public function it_can_store_mails_without_throwing(): void
    {
        $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());

        // We can just validate, that no exceptions were thrown while saving
        // the message.
        $this->assertTrue(true);
    }

    /**
     * @depends it_can_store_mails_without_throwing
     * @test
     */
    public function it_can_retrieve_a_stored_mail(): void
    {
        $stored = $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $id = $this->storage->id($stored);

        $retrieved = $this->storage->find($id);

        $this->assertNotNull($retrieved);
        $this->assertTrue($retrieved->is($stored));
    }

    /**
     * @depends it_can_store_mails_without_throwing
     * @test
     */
    public function it_can_paginate_a_stored_mail(): void
    {
        $emptyPage = $this->storage->paginate(1, 10);
        $this->assertFalse($emptyPage->hasMorePages());
        $this->assertTrue($emptyPage->isEmpty());

        foreach (range(1, 15) as $i) {
            $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        }

        $firstPage = $this->storage->paginate(1, 10);
        $this->assertTrue($firstPage->hasMorePages());
        $this->assertCount(10, $firstPage->items());
    }

    /**
     * @depends it_can_store_mails_without_throwing
     * @depends it_can_retrieve_a_stored_mail
     * @test
     */
    public function it_can_delete_a_stored_mail(): void
    {
        $stored = $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $id = $this->storage->id($stored);

        $this->storage->delete($id);

        $retrieved = $this->storage->find($id);
        $this->assertNull($retrieved);
    }

    /**
     * @depends it_can_store_mails_without_throwing
     * @test
     */
    public function it_can_count_mails(): void
    {
        $initialCount = $this->storage->count();

        $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $this->assertEquals($initialCount + 1, $this->storage->count());

        $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $this->assertEquals($initialCount + 2, $this->storage->count());
    }

    /**
     * @depends it_can_store_mails_without_throwing
     * @depends it_can_retrieve_a_stored_mail
     * @test
     */
    public function it_can_delete_old_mails(): void
    {
        $old = $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $oldId = $this->storage->id($old);

        // Lets travel to the future, so the mail we just stored is now over
        // one week old
        Carbon::setTestNow(Carbon::now()->addWeeks(2));

        // This newly stored mail should not be deleted!
        $new = $this->storage->save(TestMails::emptySwift(), TestMails::emptyStoredMail());
        $newId = $this->storage->id($new);

        $lastWeek = Carbon::now()->subWeek()->toString();

        $this->artisan(HouseKeepingCommand::COMMAND . " '$lastWeek'");

        $retrievedOld = $this->storage->find($oldId);
        $retreivedNew = $this->storage->find($newId);

        $this->assertNull($retrievedOld);
        $this->assertNotNull($retreivedNew);
    }

    /**
     * @test
     */
    public function it_can_store_and_retrieve_attachments(): void
    {
        $attachmentName = 'test-image.png';
        $mail = $this->storage->save(
            TestMails::swiftWithAttachment($attachmentName),
            TestMails::emptyStoredMail()
        );

        $found = $this->storage->findAttachment($mail, 0);

        $this->assertNotNull($found);
        $this->assertEquals($attachmentName, $found->name);
    }
}
