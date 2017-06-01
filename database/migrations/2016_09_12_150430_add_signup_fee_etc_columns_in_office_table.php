<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignupFeeEtcColumnsInOfficeTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('offices', function(Blueprint $table) {
			$table->integer('length')->after('features')->unsigned();
			$table->integer('width')->after('length')->unsigned();
			$table->decimal('signup_fee', 6, 2)->after('capacity')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('offices', function(Blueprint $table) {
			$table->dropColumn('length');
			$table->dropColumn('width');
			$table->dropColumn('signup_fee');
		});
	}

}
