<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedEmailToReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('reviews', function(Blueprint $table)
        {
            $table->string('email')->after('city');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('reviews', function(Blueprint $table)
        {
            $table->dropColumn('email');
        });
	}

}
