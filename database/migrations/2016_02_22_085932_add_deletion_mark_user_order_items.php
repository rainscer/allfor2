<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletionMarkUserOrderItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('order_items', function(Blueprint $table)
		{
			$table->boolean('deletion_mark_user')
				->default(0)
				->after('product_sku');
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('order_items', function(Blueprint $table)
		{
			$table->dropColumn('deletion_mark_user');
		});*/
	}

}
