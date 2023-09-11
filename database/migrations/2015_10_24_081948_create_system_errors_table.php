<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('system_errors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')
				->index();
			$table->text('error');
			$table->text('ip_address');
			$table->text('stack_trace');
			$table->timestamps();

			$table->index('created_at');
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::drop('system_errors');*/
	}

}
