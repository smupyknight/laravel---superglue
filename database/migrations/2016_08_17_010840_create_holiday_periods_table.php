<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayPeriodsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('holiday_periods', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('account_id')->unsigned();
			$table->date('start_date');
			$table->date('end_date');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('account_id')->references('id')->on('accounts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('holiday_periods');
	}

}
