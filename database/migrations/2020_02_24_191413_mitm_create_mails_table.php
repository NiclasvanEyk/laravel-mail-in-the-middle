<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use VanEyk\MITM\Storage\Implementations\Database\DatabaseStorage;

class MitmCreateMailsTable extends Migration
{
    const TABLE = DatabaseStorage::TABLE_PREFIX . '_mails';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at')
                ->useCurrent()
                ->index();

            // Metadata
            $table->text('subject')->default('');
            $table->text('from');
            $table->text('to');
            $table->integer('priority');
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();

            // Content
            $table->longText('rendered');
            $table->longText('viewData');
            $table->longText('src');
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
