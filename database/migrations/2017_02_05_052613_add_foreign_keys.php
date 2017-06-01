<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mentor_bookings', function($table) {
			$table->dropForeign(['schedule_id']);
			$table->foreign('schedule_id')->references('id')->on('mentor_schedule')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mentor_bookings', function($table) {
			$table->dropForeign(['schedule_id']);
			$table->foreign('schedule_id')->references('id')->on('mentor_schedule');
		});
	}

}
