<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatalogProductsIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*DB::statement('ALTER TABLE catalog_products ADD FULLTEXT desc_ru(description_ru)');
		DB::statement('ALTER TABLE catalog_products ADD FULLTEXT desc_en(description_en)');
		DB::statement('ALTER TABLE catalog_products ADD FULLTEXT desc_ua(description_ua)');*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table(
			'catalog_products',
			function ($table) {
				$table->dropColumn('seller_id');
				$table->dropIndex('desc_ru');
				$table->dropIndex('desc_en');
				$table->dropIndex('desc_ua');
			}
		);*/
	}

}
