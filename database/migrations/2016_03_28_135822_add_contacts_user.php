<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactsUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('users', function(Blueprint $table)
		{
			$table->text('contacts')
				->nullable();
			$table->dropColumn('d_city');
			$table->dropColumn('d_region');
			$table->dropColumn('d_address');
			$table->dropColumn('d_index');
			$table->dropColumn('d_phone');
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('contacts');
		});*/
	}

}
