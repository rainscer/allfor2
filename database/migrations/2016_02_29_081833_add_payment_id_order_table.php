<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentIdOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->decimal('payment_id')
				->nullable()
				->after('delivery_cost');
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
			$table->dropColumn('payment_id');
		});*/
	}

}
