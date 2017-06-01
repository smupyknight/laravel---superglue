<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('description', 500);
			$table->string('location', 255);
			$table->datetime('start_time')->nullable();
			$table->datetime('finish_time')->nullable();
			$table->tinyInteger('paid');
			$table->string('ticket_link', 255);
			$table->enum('status', ['Draft','Published']);
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
