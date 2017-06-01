<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RelabelStartEndDatesInMentorSchedule extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mentor_schedule', function($table) {
			$table->renameColumn('date_start', 'start_date');
			$table->renameColumn('date_end', 'end_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mentor_schedule', function($table) {
			$table->renameColumn('start_date', 'date_start');
			$table->renameColumn('end_date', 'date_end');
		});
	}

}
