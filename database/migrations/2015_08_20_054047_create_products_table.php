<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('catalog_products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_en');
			$table->string('name_ru');
			$table->string('slug')->unique();
			$table->integer('sku');
			$table->decimal('price');
			$table->integer('new');
			$table->integer('weight');
			$table->string('image_file');
			$table->string('image_local');
			$table->integer('upi_id');
			$table->text('description_ru');
			$table->text('description_en');
			$table->integer('likes');
			$table->integer('views');
			$table->integer('sold');
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
		/*Schema::drop('catalog_products');*/
	}

}
