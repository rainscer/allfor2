<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAdvertisingCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('item_advertising_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_campaign')->unsigned();
            $table->integer('number_of_visits')->default(0);
            $table->integer('number_of_phones')->default(0);
            $table->integer('number_of_deals')->default(0);
            $table->float('campaign_cost')->default(0);
            $table->float('campaign_profit')->default(0);
            $table->boolean('flag_changed')->default(true);
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::dropIfExists('item_advertising_campaigns');*/
    }
}
