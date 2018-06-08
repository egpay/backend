<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTempDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temp_data', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('type', 65535);
			$table->text('data');
			$table->integer('create_id');
			$table->text('create_type', 65535);
			$table->integer('reviewed_id')->nullable();
			$table->dateTime('reviewed_at')->nullable();
			$table->timestamps();
			$table->dateTime('deleted_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('temp_data');
	}

}
