<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookingCreditFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookings', function(Blueprint $table) {
			$table->dropColumn('cost');
		});

		Schema::table('booking_rooms', function(Blueprint $table) {
			$table->integer('credits_per_hour')->unsigned()->after('room_id');
		});

		Schema::table('rooms', function(Blueprint $table) {
			$table->renameColumn('num_credits', 'credits_per_hour');
			$table->dropColumn('features');
		});

		Schema::create('booking_reminders', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('booking_id')->unsigned()->index();
			$table->datetime('remind_at');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bookings', function(Blueprint $table) {
			$table->decimal('cost', 6, 2)->after('reminder');
		});

		Schema::table('booking_rooms', function(Blueprint $table) {
			$table->dropColumn('credits_per_hour');
		});

		Schema::table('rooms', function(Blueprint $table) {
			$table->renameColumn('credits_per_hour', 'num_credits');
			$table->text('features')->after('description');
		});

		Schema::drop('booking_reminders');
	}

}
