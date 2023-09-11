<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('order_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id');
			$table->integer('product_id');
			$table->text('product_name');
			$table->integer('product_quantity');
			$table->decimal('product_price');
			$table->integer('product_upi');
			$table->text('product_sku');
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
		/*Schema::drop('order_items');*/
	}

}
