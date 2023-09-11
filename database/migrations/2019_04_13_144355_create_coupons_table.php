<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('coupons', function(Blueprint $table)
        {
            $table->engine = "InnoDB";

            $table->increments('id');
            $table->string('code');
            $table->integer('count');
            $table->text('amount');
            $table->date('expired_at');
            $table->timestamps();
        });

        Schema::table('orders', function(Blueprint $table)
        {
            $table->integer('coupon_id')->nullable()->unsigned();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('orders', function(Blueprint $table)
        {
            $table->dropColumn('coupon_id');
        });

	    Schema::drop('coupons');
	}

}
