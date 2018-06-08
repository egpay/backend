<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMerchantStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('merchant_staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('merchant_staff_group_id')->unsigned()->index('merchant_staff_merchant_staff_group_id_foreign');
			$table->text('branches', 65535);
			$table->string('firstname');
			$table->string('lastname');
			$table->string('national_id', 25)->unique();
			$table->string('email', 100)->unique();
			$table->string('password');
			$table->string('remember_token', 100)->nullable();
			$table->string('mobile');
			$table->text('address', 65535)->nullable();
			$table->date('birthdate')->nullable();
			$table->enum('status', array('active','in-active'))->default('active');
			$table->boolean('must_change_password')->default(1);
			$table->dateTime('lastlogin')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->enum('language_key', array('ar','en'))->default('ar');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('merchant_staff');
	}

}
