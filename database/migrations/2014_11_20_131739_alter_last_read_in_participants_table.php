<?php

use Illuminate\Database\Migrations\Migration;

class AlterLastReadInParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::statement('ALTER TABLE `participants` CHANGE COLUMN `last_read` `last_read` timestamp NULL DEFAULT NULL;');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
