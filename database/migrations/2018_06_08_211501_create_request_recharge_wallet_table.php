<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestRechargeWalletTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_recharge_wallet', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('staff_id');
			$table->enum('transfer_type', array('in','out'));
			$table->integer('from_wallet_id');
			$table->integer('to_wallet_id')->nullable();
			$table->decimal('amount', 65);
			$table->enum('status', array('request','approved','disapproved'))->default('request');
			$table->integer('action_staff_id')->nullable();
			$table->text('action_comment', 65535)->nullable();
			$table->integer('transaction_id');
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
		Schema::drop('request_recharge_wallet');
	}

}
