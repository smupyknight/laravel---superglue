<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMembershipIdToAccountIdTimeline extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE timeline CHANGE membership_id account_id INT(10) UNSIGNED NOT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	   DB::statement('ALTER TABLE timeline CHANGE account_id membership_id INT(10) UNSIGNED NOT NULL');
	}

}
