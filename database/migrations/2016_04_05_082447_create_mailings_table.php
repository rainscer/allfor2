<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('mailings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('subject');
			$table->string('participants');
			$table->text('body');
			$table->integer('hit')
				->default(0);
			$table->boolean('scheduled')
				->default(0);
			$table->timestamps();
			$table->softDeletes();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::drop('mailings');*/
	}

}
