<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->string('company_name', 100)->after('phone');
			$table->date('dob')->nullable()->after('email');
			$table->string('instagram_handle', 50)->after('twitter_handle');
			$table->boolean('accepts_terms')->after('linkedin_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('company_name');
			$table->dropColumn('dob');
			$table->dropColumn('instagram_handle');
			$table->dropColumn('accepts_terms');
		});
	}

}
