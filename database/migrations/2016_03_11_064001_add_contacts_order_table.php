<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactsOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('orders', function(Blueprint $table)
		{
			$table->text('contacts')
				->nullable();
			$table->dropColumn('d_user_name');
			$table->dropColumn('d_user_city');
			$table->dropColumn('d_user_region');
			$table->dropColumn('d_user_address');
			$table->dropColumn('d_user_index');
			$table->dropColumn('d_user_phone');
			$table->dropColumn('d_user_email');
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
			$table->dropColumn('contacts');
		});*/
	}

}
