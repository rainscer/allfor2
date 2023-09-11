<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealViewsCatalogProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('catalog_products', function(Blueprint $table)
		{
			$table->integer('real_views')
				->default(0);
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('catalog_products', function(Blueprint $table)
		{
			$table->dropColumn('real_views');
		});*/
	}

}
