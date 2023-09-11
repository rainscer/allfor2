<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundedNumberToItemAdvertisingCampaign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /*Schema::table('item_advertising_campaigns', function(Blueprint $table)
        {
            $table->integer('number_of_refunded')->default(0);
            $table->integer('product_id')->default(0);
        });*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
