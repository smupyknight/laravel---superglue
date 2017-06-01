<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookingRooms extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('booking_rooms', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('booking_id')->unsigned()->index();
			$table->integer('room_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('rooms', function(Blueprint $table) {
			$table->integer('group_id')->unsigned()->nullable()->index()->after('space_id');
			$table->integer('num_credits')->unsigned()->after('capacity');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('booking_rooms');

		Schema::table('rooms', function(Blueprint $table) {
			$table->dropColumn('group_id');
			$table->dropColumn('num_credits');
		});
	}

}
