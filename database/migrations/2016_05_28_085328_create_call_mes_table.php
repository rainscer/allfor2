<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallMesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('call_mes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('phone');
			$table->timestamp('call_time');
			$table->boolean('completed')
				->default(0);
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
		/*Schema::drop('call_mes');*/
	}

}
