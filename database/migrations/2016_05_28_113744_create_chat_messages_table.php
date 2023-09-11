<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('chat_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('chat_id');
			$table->text('body');
			$table->boolean('read')
				->default(0);
			$table->boolean('participant_id');
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
		/*Schema::drop('chat_messages');*/
	}

}
