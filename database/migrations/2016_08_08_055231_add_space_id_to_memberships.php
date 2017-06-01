<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpaceIdToMemberships extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('memberships', function (Blueprint $table) {
			$table->integer('space_id')->unsigned()->after('plan_id');

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
		Schema::table('memberships', function (Blueprint $table) {
			$table->dropForeign('memberships_space_id_foreign');
		});
		Schema::table('memberships', function (Blueprint $table) {
			$table->dropColumn('space_id');
		});
	}

}
