<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDeliveryAndTotalType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement('ALTER TABLE `orders` CHANGE `order_total` `order_total` VARCHAR(20) NOT NULL, CHANGE `delivery_cost` `delivery_cost` VARCHAR(20) NOT NULL;');
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
