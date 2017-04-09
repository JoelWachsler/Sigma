<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormulasAdded extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('formulas', function($table)
		{
			$table->increments('id')->unique();
            $table->text('name');
            $table->text('content');
            $table->integer('searched_times')->unsigned();
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
		Schema::drop('formulas');
	}

}
