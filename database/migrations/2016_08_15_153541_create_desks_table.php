<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesksTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('desks', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('room_id')->unsigned();
			$table->string('name', 255);
			$table->decimal('cost', 6, 2)->unsigned();
			$table->integer('capacity')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('room_id')->references('id')->on('rooms');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('desks');
	}

}
