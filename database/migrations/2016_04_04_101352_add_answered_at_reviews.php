<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnsweredAtReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('reviews', function(Blueprint $table)
		{
            $table->timestamp('answered_at')
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
		/*Schema::table('reviews', function(Blueprint $table)
		{
            $table->dropColumn('answered_at');
		});*/
	}

}
