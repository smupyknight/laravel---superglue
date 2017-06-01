<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelineTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timeline', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('membership_id')->unsigned()->nullable();
			$table->text('message');
			$table->enum('type', ['info','system','alert']);
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('membership_id')->references('id')->on('memberships');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('timeline');
	}

}
