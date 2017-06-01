<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDesksTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('desks', function(Blueprint $table) {
			$table->dropColumn('capacity');
			$table->dropForeign('desks_room_id_foreign');
			$table->dropColumn('room_id');
			$table->integer('space_id')->unsigned();

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
		Schema::table('desks', function(Blueprint $table) {
			$table->integer('capacity');
			$table->integer('room_id')->unsigned();
			$table->foreign('room_id')->references('id')->on('rooms');

			$table->dropForeign('desks_space_id_foreign');
			$table->dropColumn('space_id');
		});
	}

}
