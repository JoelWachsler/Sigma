<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomHomeworks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classroom_homeworks', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('classroom_id')->unsigned();
            $table->string('message');
            $table->timestamp('deadline');
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
		Schema::drop('classroom_homeworks');
	}

}
