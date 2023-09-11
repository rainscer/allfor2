<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdCatalogCatrgoties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('catalog_categories', function(Blueprint $table)
		{
            $table->integer('parent_id')->nullable();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('catalog_categories', function(Blueprint $table)
		{
            $table->dropColumn('parent_id');
		});*/
	}

}
