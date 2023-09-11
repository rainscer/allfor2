<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryCostOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->decimal('delivery_cost')
				->default(0)
				->after('order_total');
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->dropColumn('delivery_cost');
		});*/
	}

}
