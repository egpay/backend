<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->integer('id', true);
			$table->integer('merchant_id');
			$table->enum('type', array('product','service'));
			$table->string('code', 32);
			$table->string('description_ar', 500);
			$table->string('description_en', 500);
			$table->float('reward');
			$table->enum('reward_type', array('fixed','percentage'));
			$table->integer('quantity');
			$table->text('users')->nullable();
			$table->text('items')->nullable();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('staff_id');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('coupons');
	}

}
