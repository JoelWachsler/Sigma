<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chapters', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->text('desc');
            $table->text('review');
            $table->integer('book_id')->unsigned();
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
		Schema::drop('chapters');
	}

}
