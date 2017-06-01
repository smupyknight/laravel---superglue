<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('twitter_handle')->after('last_name');
			$table->string('salutation')->after('email');
			$table->string('industry')->after('phone');
			$table->string('city')->after('address');
			$table->string('zip')->after('address');
			$table->string('job_title')->after('phone');
			$table->text('message')->after('type');
			$table->dateTime('create_date')->after('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('twitter_handle');
			$table->dropColumn('salutation');
			$table->dropColumn('industry');
			$table->dropColumn('city');
			$table->dropColumn('zip');
			$table->dropColumn('job_title');
			$table->dropColumn('message');
			$table->dropColumn('create_date');
		});
	}

}
