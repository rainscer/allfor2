<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('chat_participants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_session_id')
				->nullable();
			$table->integer('chat_id');
			$table->boolean('support')
				->default(0);
			$table->boolean('is_typing')
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
		/*Schema::drop('chat_participants');*/
	}

}
