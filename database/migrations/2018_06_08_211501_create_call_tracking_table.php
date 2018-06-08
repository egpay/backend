<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCallTrackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('call_tracking', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('type', array('in','out'));
			$table->string('phone_number');
			$table->dateTime('calltime');
			$table->string('caller_name');
			$table->text('details');
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
		Schema::drop('call_tracking');
	}

}
