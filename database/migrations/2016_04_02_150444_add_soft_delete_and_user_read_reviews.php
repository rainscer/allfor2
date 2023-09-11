<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteAndUserReadReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('reviews', function(Blueprint $table)
		{
			$table->softDeletes();
			$table->boolean('user_unread')
				->default(false);
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('reviews', function(Blueprint $table)
		{
			$table->dropColumn('user_unread');
			$table->dropSoftDeletes();
		});*/
	}

}
