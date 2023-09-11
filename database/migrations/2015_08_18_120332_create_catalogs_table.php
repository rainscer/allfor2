<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('catalog_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name_ru');
			$table->string('name_en');
			$table->string('description_ru');
			$table->string('description_en');
			$table->string('slug')->unique();
			$table->integer('level');
			$table->integer('left_key');
			$table->integer('right_key');
			$table->string('image');
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::drop('catalog_categories');*/
	}

}
