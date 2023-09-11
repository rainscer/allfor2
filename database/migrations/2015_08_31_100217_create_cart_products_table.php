<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('cart_products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cart_id');
			$table->integer('product_id');
			$table->integer('quantity');
			$table->decimal('price');
			$table->integer('upi_id');
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
		/*Schema::drop('cart_products');*/
	}

}
