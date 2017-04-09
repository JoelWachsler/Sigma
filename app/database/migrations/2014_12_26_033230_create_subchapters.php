<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubchapters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subchapters', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->text('desc');
            $table->text('review');
            $table->integer('chapter_id')->unsigned();
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
		Schema::drop('subchapters');
	}

}
