<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('catalog_articles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title_ua');
			$table->string('title_ru');
			$table->string('title_en');
			$table->string('text_ua');
			$table->string('text_ru');
			$table->string('text_en');
			$table->string('slug')->unique();
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
		//
	}

}
