<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpaceIdAndLocationOtherFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->integer('space_id')->unsigned()->after('id')->nullable();
			DB::statement('ALTER TABLE events CHANGE location location_other VARCHAR(255)');

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
		Schema::table('events', function (Blueprint $table) {
			$table->dropForeign('events_space_id_foreign');
			$table->dropColumn('space_id');
			DB::statement('ALTER TABLE events CHANGE location_other location VARCHAR(255)');
		});
	}

}
