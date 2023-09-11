<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletionMarkOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table(
			'orders',
			function ($table) {
				$table->boolean('deletion_mark')
					->default(0)
					->after('api');
			}
		);*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table(
			'orders',
			function ($table) {
				$table->dropColumn('deletion_mark');
			}
		);*/
	}

}
