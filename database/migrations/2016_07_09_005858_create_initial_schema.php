<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialSchema extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('space_id')->unsigned()->references('id')->on('spaces');
			$table->integer('membership_id')->unsigned()->references('id')->on('memberships');
			$table->string('name', 250);
			$table->string('abn', 11);
			$table->string('industry', 50);
			$table->date('date_started');
			$table->string('billing_email', 50);
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		});

		Schema::create('company_stats', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->unsigned()->references('id')->on('companies');
			$table->string('month', 7);
			$table->integer('employees')->unsigned();
			$table->string('revenue', 100);
			$table->string('investment', 100);
			$table->dateTime('created_at');
			$table->unique(['company_id', 'month']);
		});

		Schema::create('user_stats', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->references('id')->on('users');
			$table->text('goal');
			$table->dateTime('created_at');
		});

		Schema::create('spaces', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
		});

		Schema::create('memberships', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
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
		Schema::drop('companies');
		Schema::drop('spaces');
		Schema::drop('memberships');
		Schema::drop('company_stats');
		Schema::drop('user_stats');
	}

}
