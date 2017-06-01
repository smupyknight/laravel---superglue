<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteConnectionsAddLikeTourAndHasVisited extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('is_looking_for_connections');
			$table->tinyInteger('like_tour')->default(0)->after('is_public');
			$table->tinyInteger('has_visited')->default(0)->after('is_public');
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
			$table->tinyInteger('is_looking_for_connections')->default(0)->after('is_public');
			$table->dropColumn('like_tour');
			$table->dropColumn('has_visited');
		});
	}

}
