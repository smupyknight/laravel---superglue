<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignupFeeInDeskTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('desks', function(Blueprint $table) {
			$table->decimal('signup_fee', 6, 2)->after('name')->unsigned();
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
			$table->dropColumn('signup_fee');
		});
	}

}
