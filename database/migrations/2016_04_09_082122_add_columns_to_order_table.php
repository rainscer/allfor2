<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->integer('last_office_index')
				->nullable();
			$table->text('delivery_description')
				->nullable();
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
			$table->dropColumn('last_office_index');
			$table->dropColumn('delivery_description');
		});*/
	}

}
