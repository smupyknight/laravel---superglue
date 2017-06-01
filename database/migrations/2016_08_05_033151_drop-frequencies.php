<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFrequencies extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('memberships', function(Blueprint $table) {
			$table->dropColumn('frequency');
		});

		Schema::table('plans', function(Blueprint $table) {
			$table->dropColumn('frequency');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('memberships', function(Blueprint $table) {
			$table->enum('frequency', ['day','week','fortnight','month','year'])->after('cost');
		});

		Schema::table('plans', function(Blueprint $table) {
			$table->enum('frequency', ['day','week','fortnight','month','year'])->after('cost');
		});
	}

}
