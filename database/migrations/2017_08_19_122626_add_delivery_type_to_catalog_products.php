<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryTypeToCatalogProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('catalog_products', function(Blueprint $table)
		{
			$table->boolean('delivery_type')->default(0);
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
			//
		});*/
	}

}
