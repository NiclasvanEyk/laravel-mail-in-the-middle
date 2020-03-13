<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VanEyk\MITM\Models\StoredMail;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;

class MitmCreateAttachmentsTable extends Migration
{
    const TABLE = DatabaseStorage::TABLE_PREFIX . '_attachments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('mail_id');
            $table->foreign('mail_id')
                ->references('id')
                ->on((new StoredMail())->getTable());
            $table->longText('name');
            $table->string('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}
