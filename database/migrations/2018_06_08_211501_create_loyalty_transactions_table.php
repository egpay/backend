<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLoyaltyTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('loyalty_transactions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('transaction_id')->nullable();
			$table->float('point', 10, 0);
			$table->integer('from_id');
			$table->integer('to_id');
			$table->enum('status', array('pending','paid','reverse'));
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('loyalty_transactions');
	}

}
