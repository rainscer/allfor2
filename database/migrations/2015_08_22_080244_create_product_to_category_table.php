<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductToCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /*Schema::create('register_product_categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('product_id');
			$table->boolean('active');
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
		/*Schema::drop('register_product_categories');*/
	}

}
