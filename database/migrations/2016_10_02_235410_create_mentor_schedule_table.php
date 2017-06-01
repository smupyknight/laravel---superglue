<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMentorScheduleTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mentor_schedule', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('mentor_id')->unsigned();
			$table->integer('space_id')->unsigned();
			$table->date('date');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('mentor_id')->references('id')->on('users');
			$table->foreign('space_id')->references('id')->on('spaces');
		});

		Schema::create('mentor_bookings', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('mentor_id')->unsigned();
			$table->integer('member_id')->unsigned();
			$table->integer('schedule_id')->unsigned();
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('mentor_id')->references('id')->on('users');
			$table->foreign('member_id')->references('id')->on('users');
			$table->foreign('schedule_id')->references('id')->on('mentor_schedule');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mentor_bookings');
		Schema::drop('mentor_schedule');
	}

}
