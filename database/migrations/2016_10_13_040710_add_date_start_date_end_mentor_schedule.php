<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateStartDateEndMentorSchedule extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mentor_schedule', function (Blueprint $table) {
			$table->dateTime('date_end')->after('space_id');
			$table->dateTime('date_start')->after('space_id');

			$table->dropColumn('date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mentor_schedule', function (Blueprint $table) {
			$table->date('date')->after('space_id');

			$table->dropColumn('date_end');
			$table->dropColumn('date_start');
		});
	}

}
