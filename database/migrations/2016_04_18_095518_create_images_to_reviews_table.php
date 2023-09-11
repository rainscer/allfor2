<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesToReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('images_to_reviews', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('review_id');
			$table->string('image');
			$table->timestamps();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::drop('images_to_reviews');*/
	}

}
