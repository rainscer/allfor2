<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignNameCallMes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::table('call_mes', function(Blueprint $table)
		{
            $table->string('campaign_name')->nullable();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		/*Schema::table('call_mes', function(Blueprint $table)
		{
            Schema::dropColumn('campaign_name');
		});*/
	}

}
