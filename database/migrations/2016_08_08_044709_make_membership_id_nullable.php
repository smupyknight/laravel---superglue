<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMembershipIdNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$pdo = DB::connection()->getPdo();

		$pdo->exec('ALTER TABLE  `users` CHANGE  `membership_id`  `membership_id` INT( 10 ) UNSIGNED NULL DEFAULT NULL ;
');
		$pdo->exec('UPDATE  `users` SET  `membership_id` = NULL WHERE  `membership_id` =0;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$pdo = DB::connection()->getPdo();

		$pdo->exec('ALTER TABLE  `users` CHANGE  `membership_id`  `membership_id` INT( 10 ) UNSIGNED NOT NULL ;');
	}

}
