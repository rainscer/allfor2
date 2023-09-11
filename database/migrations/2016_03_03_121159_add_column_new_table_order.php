<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNewTableOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->boolean('new')
				->default(0)
				->after('deletion_mark');
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
			$table->dropColumn('new');
		});*/
	}

}
