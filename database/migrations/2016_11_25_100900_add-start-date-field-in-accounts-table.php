<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateFieldInAccountsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function (Blueprint $table) {
			$table->date('start_date')->after('email');
		});
		DB::statement('UPDATE accounts SET start_date = DATE_ADD(created_at, INTERVAL 10 HOUR)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function (Blueprint $table) {
			$table->dropColumn('start_date');
		});
	}

}

