<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->string('name', 100)->unique('name');
			$table->text('value', 65535)->nullable();
			$table->string('shown_name_ar', 150);
			$table->string('shown_name_en', 150);
			$table->string('input_type', 100);
			$table->text('option_list', 65535)->nullable();
			$table->string('group_name');
			$table->integer('sort');
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
		Schema::drop('settings');
	}

}
