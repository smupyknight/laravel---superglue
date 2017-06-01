<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rooms', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('space_id')->unsigned();
			$table->string('name', 255);
			$table->text('features', 255);
			$table->integer('capacity')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('space_id')->references('id')->on('spaces');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rooms');
	}

}
