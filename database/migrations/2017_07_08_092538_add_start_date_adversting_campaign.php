<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateAdverstingCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('advertising_campaigns', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('advertising_campaigns', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });*/
    }
}
