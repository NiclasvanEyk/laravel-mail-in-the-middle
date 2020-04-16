<?php

namespace Test\Suite\Storage;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Test\Cases\Storage\MailStorageTestCase;

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

    /**
     * This test is not really implemented very robustly, as it only checks, if
     * the query plan for pagination contains the substring 'USING INDEX'.
     *
     * Moreover this is highly specific to the testing sqlite, but other
     * databases should hopefully also use the index if sqlite does.
     *
     * This test should act as a way of ensuring that the performance is fine.
     *
     * @test
     */
    public function it_uses_an_index_for_pagination()
    {
        DB::listen(function (QueryExecuted $execution) {
            if (Str::contains(Str::lower($execution->sql), 'explain')) {
                return;
            }

            $explanations = DB::select("EXPLAIN QUERY PLAN $execution->sql");
            $this->assertCount(1, $explanations);

            $detail = $explanations[0]->detail;
            $this->assertStringContainsString('USING INDEX', $detail);
        });

        $this->storage->paginate();
    }
}
