<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyOptionToRecurrence extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$pdo = DB::connection()->getPdo();

		$pdo->exec("ALTER TABLE membership_features CHANGE recurrance recurrance ENUM('Daily','Weekly','Fortnightly','Monthly','Yearly')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$pdo = DB::connection()->getPdo();

		$pdo->exec("ALTER TABLE membership_features CHANGE recurrance recurrance ENUM('Weekly','Fortnightly','Monthly','Yearly')");
	}

}
