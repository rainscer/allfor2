<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortCatalogProductImages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('catalog_product_images', function(Blueprint $table)
		{
            $table->integer('sort')->default(0);
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('catalog_product_images', function(Blueprint $table)
		{
            $table->dropColumn('sort');
		});*/
	}

}
