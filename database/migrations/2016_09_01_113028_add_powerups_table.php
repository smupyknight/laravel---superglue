<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPowerupsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('power_ups', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 255);
			$table->string('description', 500);
			$table->string('link', 255);
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		 });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('power_ups');
	}

}
