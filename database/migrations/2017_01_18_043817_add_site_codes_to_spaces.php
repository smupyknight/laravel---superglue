<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteCodesToSpaces extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('spaces', function (Blueprint $table) {
			$table->string('site_code')->after('timezone');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('spaces', function (Blueprint $table) {
			$table->dropColumn('site_code');
		});
	}

}
