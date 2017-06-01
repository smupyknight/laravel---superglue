<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MentorRequestTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mentor_requests', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned()->index();
			$table->integer('mentor_id')->unsigned()->index();
			$table->text('topic');
			$table->timestamps();

			$table->foreign('member_id')->references('id')->on('users');
			$table->foreign('mentor_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mentor_requests');
	}

}
