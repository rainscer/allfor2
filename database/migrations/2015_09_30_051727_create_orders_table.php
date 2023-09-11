<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('order_number');
			$table->decimal('order_total')
				->default(0);
			$table->string('order_status',50);
			$table->text('d_user_name');
			$table->integer('d_user_city');
			$table->integer('d_user_region');
			$table->text('d_user_address');
			$table->string('d_user_index',8);
			$table->string('d_user_phone',20);
			$table->string('d_user_email',150);
			$table->boolean('api');
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
		/*Schema::drop('orders');*/
	}

}
