<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbExcEngine extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id')->unique();
            $table->integer('chapter_id')->unsigned();
            $table->integer('subchapter_id')->unsigned();
            $table->integer('chapter_order')->unsigned();
            $table->text('content');
            $table->text('latex_solve');
            $table->text('latex_answer');
            $table->text('answer');
            $table->boolean('answer_type');
            $table->tinyInteger('difficulty')->unsigned();
            $table->integer('height')->unsigned();
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
		Schema::drop('tasks');
	}

}
