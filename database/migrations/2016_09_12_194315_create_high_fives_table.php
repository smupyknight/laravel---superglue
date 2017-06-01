<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighFivesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('high_fives', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index();
			$table->dateTime('created_at');

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('created_by')->references('id')->on('users');
		});

		Schema::table('users', function (Blueprint $table) {
			$table->integer('num_high_fives')->after('like_tour');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('high_fives');

		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('num_high_fives');
		});
	}

}
