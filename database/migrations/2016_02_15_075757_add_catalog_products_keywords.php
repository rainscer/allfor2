<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCatalogProductsKeywords extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table(
			'catalog_products',
			function ($table) {
				$table->text('meta_keywords_ru')
					->nullable()
					->after('description_en');

				$table->text('meta_keywords_en')
					->nullable()
					->after('description_en');

				$table->text('meta_keywords_ua')
					->nullable()
					->after('description_en');
			}
		);*/

		/*DB::statement('ALTER TABLE catalog_products ADD FULLTEXT name_keyword_ru(name_ru, meta_keywords_ru)');
		DB::statement('ALTER TABLE catalog_products ADD FULLTEXT name_keyword_en(name_en, meta_keywords_en)');
		DB::statement('ALTER TABLE catalog_products ADD FULLTEXT name_keyword_ua(name_ua, meta_keywords_ua)');*/
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
				$table->dropColumn('meta_keywords_ru');
				$table->dropColumn('meta_keywords_en');
				$table->dropColumn('meta_keywords_ua');
				$table->dropIndex('name_keyword_ru');
				$table->dropIndex('name_keyword_en');
				$table->dropIndex('name_keyword_ua');
			}
		);*/
	}

}