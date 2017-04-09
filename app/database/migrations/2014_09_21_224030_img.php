<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Img extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('img', function(Blueprint $table)
		{
			$table->increments('id')->unique();
            $table->integer('user_id')->unsigned();
            $table->string('unique_id');
            $table->binary('data');
            $table->string('type');
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
		Schema::drop('img');
	}

}
