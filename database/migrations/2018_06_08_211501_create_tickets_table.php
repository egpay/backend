<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('merchant_id')->nullable();
			$table->string('invoiceable_type', 100);
			$table->integer('invoiceable_id')->nullable();
			$table->string('subject', 500);
			$table->text('details');
			$table->string('to_type', 50)->nullable();
			$table->integer('to_id')->nullable();
			$table->enum('status', array('open','closed','done'));
			$table->integer('created_by_staff_id')->nullable();
			$table->boolean('is_seen_by_sender')->default(0);
			$table->boolean('is_seen_by_receiver')->default(0);
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
		Schema::drop('tickets');
	}

}
