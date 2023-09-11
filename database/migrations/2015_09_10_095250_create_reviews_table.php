<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('reviews', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')
				->nullable();
			$table->integer('product_id');
			$table->text('text');
			$table->string('quest',250)
				->nullable();
			$table->string('quest_city',255)
				->nullable();
			$table->boolean('active')
				->nullable();
			$table->boolean('new')
				->nullable();
			$table->integer('rating')
				->nullable();
			$table->string('type',255)
				->default('review');
			$table->text('answer')
				->nullable();
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
		/*Schema::drop('reviews');*/
	}

}
