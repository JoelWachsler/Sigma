<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 32);
            $table->tinyInteger('account_type')->unsigned();
			$table->string('email', 64)->unique();
            $table->string('first_name', 32);
            $table->string('last_name', 32);
            $table->string('img_id');
			$table->string('password', 64);
			$table->string('password_temp', 64);
            $table->string('code');
            $table->string('remember_token');
			$table->tinyInteger('active')->unsigned();
            $table->softDeletes();
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
		Schema::drop('users');
	}

}
