<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->rememberToken();
			$table->boolean('active');
			$table->string('image');
			$table->bigInteger('social_id',false,true);
			$table->integer('d_city');
			$table->integer('d_region');
			$table->text('d_address');
			$table->string('d_index',8);
			$table->string('d_phone',20);
			$table->string('activationCode',250);
			$table->boolean('isActive')->index();
			$table->string('social_url',250);
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
		/*Schema::drop('users');*/
	}

}
