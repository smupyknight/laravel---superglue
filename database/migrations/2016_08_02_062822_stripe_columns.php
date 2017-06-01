<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StripeColumns extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('memberships', function(Blueprint $table) {
			$table->string('stripe_id')->nullable();
			$table->string('card_brand')->nullable();
			$table->string('card_last_four')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('memberships', function(Blueprint $table) {
			$table->dropColumn('stripe_id');
			$table->dropColumn('card_brand');
			$table->dropColumn('card_last_four');
		});
	}

}