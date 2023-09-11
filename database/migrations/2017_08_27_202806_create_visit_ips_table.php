<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitIpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('visit_ips', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedBigInteger('ip');
            $table->boolean('refunded')->default(0);
            $table->integer('campaign_id')->default(0);
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
		/*Schema::drop('visit_ips');*/
	}

}
